<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\FinishedGood;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    // Menampilkan daftar tiket produksi di halaman awal
    public function index(): View
    {
        // Hanya tampilkan yang Planned (Menunggu) dan Processing (Sedang Dikerjakan)
        $productions = Production::with(['items.menu'])
            ->whereIn('status', ['planned', 'processing'])
            ->orderBy('plan_date', 'asc')
            ->get();

        return view('prod.index', compact('productions'));
    }

    // --- UBAH FUNGSI SHOW (Kita butuh melempar daftar bahan baku ke view) ---
    public function show(Production $production)
    {
        // 1. Load relasi tabel
        $production->load(['items.menu.ingredients', 'creator']);
        
        // Ambil hanya bahan baku yang digunakan dalam menu di tiket ini saja
        $availableIngredients = $production->items
        ->flatMap(fn($item) => $item->menu->ingredients)
        ->unique('id')
        ->values(); // Mengurutkan ulang index agar aman di JSON

        // 3. Kalkulasi ulang untuk Tampilan Dapur
        $totalCups = 0;
        $totalHpp = 0;
        $ingredientsNeeded = [];

        foreach ($production->items as $item) {
            $totalCups += $item->target_quantity;

            if ($item->menu) {
                foreach ($item->menu->ingredients as $ing) {
                    $neededQty = $ing->pivot->quantity * $item->target_quantity;
                    
                    // Kalkulasi HPP
                    $pricePerUnit = ($ing->conversion_value > 0) ? ($ing->latest_price / $ing->conversion_value) : 0;
                    $totalHpp += ($neededQty * $pricePerUnit);

                    // Kalkulasi Kebutuhan Bahan Baku vs Stok
                    if (!isset($ingredientsNeeded[$ing->id])) {
                        // Hitung total fisik (Terbuka + Tersegel)
                        $totalStock = $ing->opened_stock + ($ing->sealed_stock * $ing->conversion_value);

                        $ingredientsNeeded[$ing->id] = [
                            'name' => $ing->name,
                            'unit' => $ing->unit,
                            'needed' => 0,
                            'stock' => $totalStock
                        ];
                    }
                    $ingredientsNeeded[$ing->id]['needed'] += $neededQty;
                }
            }
        }

        // 4. Lempar SEMUA variabel ke View Dapur
        return view('prod.show', compact('production', 'availableIngredients', 'totalCups', 'totalHpp', 'ingredientsNeeded'));
    }

    // Fungsi mengubah status dari Planned menjadi Processing
    public function start(Production $production): RedirectResponse
    {
        if ($production->status === 'planned') {
            $production->update(['status' => 'processing']);
        }
        return back()->with('success', 'Status diubah ke Work in Progress. Selamat bekerja!');
    }

    // Menyelesaikan Produksi (Strict Fulfillment Logic)
    public function complete(Request $request, Production $production)
    {
        $request->validate([
            'execution_notes' => 'nullable|string',
            'wasted_materials' => 'nullable|array', // Menangkap array bahan tumpah
        ]);

        // TAHAP VALIDASI - DILAKUKAN SEBELUM MEMOTONG STOK
        $production->load('items.menu.ingredients');
        $totalDeductions = [];

        // 1. Kumpulkan semua kebutuhan stok (Resep Dasar)
        foreach ($production->items as $item) {
            foreach ($item->menu->ingredients as $ing) {
                $qtyNeeded = $ing->pivot->quantity * $item->target_quantity;
                
                if (!isset($totalDeductions[$ing->id])) {
                    $totalDeductions[$ing->id] = 0;
                }
                $totalDeductions[$ing->id] += $qtyNeeded;
            }
        }

        // 2. Tambahkan dengan laporan bahan tumpah/terbuang (Jika ada)
        if ($request->has('wasted_materials')) {
            foreach ($request->wasted_materials as $waste) {
                if (!empty($waste['id']) && !empty($waste['qty'])) {
                    if (!isset($totalDeductions[$waste['id']])) {
                        $totalDeductions[$waste['id']] = 0;
                    }
                    $totalDeductions[$waste['id']] += $waste['qty'];
                }
            }
        }

        // 3. VALIDASI FINAL: Cek ke database apakah stok fisik mencukupi
        foreach ($totalDeductions as $rm_id => $totalNeeded) {
            $rawMaterial = \App\Models\RawMaterial::find($rm_id);
            if ($rawMaterial) {
                $stokTersedia = $rawMaterial->opened_stock + ($rawMaterial->sealed_stock * $rawMaterial->conversion_value);
                
                if ($totalNeeded > $stokTersedia) {
                    // TENDANG BALIK KE HALAMAN SEBELUMNYA! TRANSAKSI BATAL.
                    return back()->with('error', "GAGAL! Total kebutuhan {$rawMaterial->name} ({$totalNeeded} {$rawMaterial->purchase_unit}) melebihi sisa stok gudang ({$stokTersedia} {$rawMaterial->purchase_unit}). Silakan hubungi Owner.");
                }
            }
        }

        // AKHIR TAHAP VALIDASI - JIKA LOLOS, BARU POTONG STOK DI DATABASE
        DB::transaction(function () use ($request, $production) {
            $production->update([
                'status' => 'completed',
                'execution_notes' => $request->execution_notes
            ]);

            // TAHAP A: Potong Bahan Baku Normal & Masukkan Stok Jadi
            foreach ($production->items as $prodItem) {
                $targetQty = $prodItem->target_quantity;
                $menu = $prodItem->menu;

                foreach ($menu->ingredients as $ing) {
                    $totalNeeded = $ing->pivot->quantity * $targetQty;
                    $rawMaterial = $ing;

                    // Logika potong stok
                    if ($rawMaterial->opened_stock < $totalNeeded) {
                        $shortage = $totalNeeded - $rawMaterial->opened_stock;
                        $packagesToOpen = ceil($shortage / $rawMaterial->conversion_value);
                        $rawMaterial->sealed_stock -= $packagesToOpen;
                        $rawMaterial->opened_stock += ($packagesToOpen * $rawMaterial->conversion_value);
                    }
                    $rawMaterial->opened_stock -= $totalNeeded;
                    $rawMaterial->save();
                }

                // Masuk Etalase (Selalu Full Target)
                if ($targetQty > 0) {
                    \App\Models\FinishedGood::create([
                        'menu_id' => $menu->id,
                        'production_id' => $production->id,
                        'initial_quantity' => $targetQty,
                        'current_quantity' => $targetQty,
                        'production_date' => now()->toDateString(),
                        'expired_date' => now()->addDays($menu->expires_in_days ?? 1)->toDateString(),
                        'status' => 'available'
                    ]);
                }
            }

            // TAHAP B: Catat dan Potong Bahan Baku Wasted (Tumpah)
            if ($request->has('wasted_materials')) {
                foreach ($request->wasted_materials as $waste) {
                    if (!empty($waste['id']) && !empty($waste['qty']) && $waste['qty'] > 0) {
                        
                        // 1. Catat ke tabel riwayat tumpah
                        \App\Models\ProductionWaste::create([
                            'production_id' => $production->id,
                            'raw_material_id' => $waste['id'],
                            'quantity' => $waste['qty']
                        ]);

                        // 2. Potong stok fisik di gudang
                        $rm = \App\Models\RawMaterial::find($waste['id']);
                        if ($rm->opened_stock < $waste['qty']) {
                            $shortage = $waste['qty'] - $rm->opened_stock;
                            $packagesToOpen = ceil($shortage / $rm->conversion_value);
                            $rm->sealed_stock -= $packagesToOpen;
                            $rm->opened_stock += ($packagesToOpen * $rm->conversion_value);
                        }
                        $rm->opened_stock -= $waste['qty'];
                        $rm->save();
                    }
                }
            }
        });

        return redirect()->route('produksi.productions.index')->with('success', 'Produksi Selesai! Bahan baku tumpah telah dipotong dari stok.');
    }
}
