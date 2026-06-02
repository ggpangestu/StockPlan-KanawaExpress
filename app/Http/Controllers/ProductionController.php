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

    // Menampilkan detail spesifik dari satu tiket produksi
    public function show(Production $production): View
    {
        // Load relasi yang dibutuhkan
        $production->load(['items.menu.ingredients']);

        // Kalkulasi Kebutuhan Bahan Baku & HPP Total
        $ingredientsNeeded = [];
        $totalHpp = 0;
        $totalCups = 0;

        foreach ($production->items as $item) {
            $totalCups += $item->target_quantity;
            
            foreach ($item->menu->ingredients as $ing) {
                if (!isset($ingredientsNeeded[$ing->id])) {
                    $ingredientsNeeded[$ing->id] = [
                        'name' => $ing->name,
                        'needed' => 0,
                        'unit' => $ing->base_unit,
                        'stock' => $ing->total_stock, // Stok fisik saat ini
                    ];
                }
                
                $qty = $ing->pivot->quantity * $item->target_quantity;
                $ingredientsNeeded[$ing->id]['needed'] += $qty;

                // Kalkulasi HPP
                $pricePerUnit = ($ing->conversion_value > 0) ? ($ing->latest_price / $ing->conversion_value) : 0;
                $totalHpp += ($qty * $pricePerUnit);
            }
        }

        $hppPerCup = $totalCups > 0 ? ($totalHpp / $totalCups) : 0;

        return view('prod.show', compact('production', 'ingredientsNeeded', 'totalHpp', 'totalCups', 'hppPerCup'));
    }

    // Fungsi mengubah status dari Planned menjadi Processing
    public function start(Production $production): RedirectResponse
    {
        if ($production->status === 'planned') {
            $production->update(['status' => 'processing']);
        }
        return back()->with('success', 'Status diubah ke Work in Progress. Selamat bekerja!');
    }

    // THE MAGIC TRICKS: Menyelesaikan Produksi
    public function complete(Request $request, Production $production): RedirectResponse
    {
        $request->validate([
            'actual_quantities' => 'required|array',
            'actual_quantities.*' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $production) {
            
            // SIHIR 1: Ubah status plan
            $production->update(['status' => 'completed']);

            foreach ($request->actual_quantities as $itemId => $actualQty) {
                if ($actualQty <= 0) continue; 
                
                $prodItem = ProductionItem::find($itemId);
                $prodItem->update(['actual_quantity' => $actualQty]);
                $menu = $prodItem->menu;

                // SIHIR 2: Memotong Bahan Baku
                foreach ($menu->ingredients as $ing) {
                    $totalNeeded = $ing->pivot->quantity * $actualQty;
                    $rawMaterial = $ing;

                    if ($rawMaterial->opened_stock < $totalNeeded) {
                        $shortage = $totalNeeded - $rawMaterial->opened_stock;
                        $packagesToOpen = ceil($shortage / $rawMaterial->conversion_value);
                        
                        $rawMaterial->sealed_stock -= $packagesToOpen;
                        $rawMaterial->opened_stock += ($packagesToOpen * $rawMaterial->conversion_value);
                    }

                    $rawMaterial->opened_stock -= $totalNeeded;
                    $rawMaterial->save();
                }

                // SIHIR 3: Masukkan ke Stok Jadi
                FinishedGood::create([
                    'menu_id' => $menu->id,
                    'production_id' => $production->id,
                    'initial_quantity' => $actualQty,
                    'current_quantity' => $actualQty,
                    'production_date' => now()->toDateString(),
                    'expired_date' => now()->addDays($menu->expires_in_days ?? 1)->toDateString(),
                    'status' => 'available'
                ]);
            }
        });

        // Setelah selesai, lemparkan kembali ke halaman index produksi
        return redirect()->route('produksi.productions.index')
            ->with('success', 'Produksi Selesai! Bahan baku otomatis dipotong dan barang masuk ke Stok Jadi.');
    }
}
