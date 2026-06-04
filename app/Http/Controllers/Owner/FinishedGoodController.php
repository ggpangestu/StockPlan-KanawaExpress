<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\FinishedGood;
use Carbon\Carbon;

class FinishedGoodController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->get('filter', 'available'); // Default lihat yang tersedia saja

        $query = FinishedGood::with(['menu', 'production'])
            ->orderBy('expired_date', 'asc'); // Yang mau basi ditaruh paling atas (FIFO)

        if ($filter === 'available') {
            $query->where('status', 'available')->where('current_quantity', '>', 0);
        } elseif ($filter === 'expired') {
            $query->where('status', 'expired');
        } elseif ($filter === 'expired_damaged') {
            $query->where('status', 'expired_damaged');
        } elseif ($filter === 'empty') {
            $query->where(function ($query) {
                $query->where('status', 'empty')
                    ->orWhere('current_quantity', 0);
            });
        }

        $goods = $query->get();

        // Menghitung statistik untuk ringkasan di atas (Cards)
        $today = Carbon::today();
        
        $stats = [
            'total_available' => FinishedGood::where('status', 'available')->where('current_quantity', '>', 0)->sum('current_quantity'),
            // Warning jika expired_date adalah hari ini atau besok
            'expiring_soon' => FinishedGood::where('status', 'available')
                                ->where('current_quantity', '>', 0)
                                ->whereBetween('expired_date', [$today, $today->copy()->addDays(1)])
                                ->count(),
            'total_expired' => FinishedGood::where('status', 'expired')->sum('current_quantity'),
            'total_expired_damaged' => FinishedGood::where('status', 'expired_damaged')->sum('current_quantity'),
        ];

        return view('owner.finished-goods.index', compact('goods', 'filter', 'stats'));
    }
}
