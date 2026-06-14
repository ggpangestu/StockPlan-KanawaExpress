<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\RawMaterial;

class DashboardController extends Controller
{
    public function index()
    {
        return match (Auth::user()->role) {
            'owner' => view('dashboard.owner'),
            'produksi' => $this->produksiDashboard(),
            'armada' => view('dashboard.armada'),
            default => abort(403),
        };
    }


    //Logika khusus untuk mengambil data Dashboard Produksi (Dapur) 
    private function produksiDashboard()
    {
        // 1. Tiket Antrean Baru (Menunggu) - Semua yang belum dieksekusi
        $tiketMenunggu = \App\Models\Production::where('status', 'planned')->count();

        // 2. Tiket Sedang Dimasak (Work in Progress) - Semua yang sedang aktif
        $tiketDiproses = \App\Models\Production::where('status', 'processing')->count();

        // 3. Total Target Porsi Aktif
        $totalPorsiAktif = \App\Models\Production::with('items')
                            ->whereIn('status', ['planned', 'processing'])
                            ->get()
                            ->sum(function ($prod) {
                                return $prod->items->sum('target_quantity');
                            });

        // 4. Peringatan Stok Bahan Baku Menipis
        $bahanMenipis = \App\Models\RawMaterial::whereRaw('(opened_stock + (sealed_stock * conversion_value)) < 10')
                            ->orderByRaw('(opened_stock + (sealed_stock * conversion_value)) ASC')
                            ->take(5)
                            ->get();

        // 5. 5 History Produksi Terakhir (yang sudah selesai)
        $riwayatProduksi = \App\Models\Production::whereNotIn('status', ['planned', 'processing'])
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();

        // 6. Produksi Paling Mendesak/Terbaru beserta menunya
        $produksiPrioritas = \App\Models\Production::with('items.menu')
                            ->whereIn('status', ['planned', 'processing'])
                            ->orderBy('plan_date', 'asc') // Ambil tanggal yang paling butuh cepat diselesaikan
                            ->first();

        return view('dashboard.produksi', compact(
            'tiketMenunggu', 
            'tiketDiproses', 
            'totalPorsiAktif', 
            'bahanMenipis',
            'riwayatProduksi',
            'produksiPrioritas'
        ));
    }
}