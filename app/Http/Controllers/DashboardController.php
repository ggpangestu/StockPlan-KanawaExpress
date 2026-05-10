<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return match (Auth::user()->role) {
            'owner' => view('dashboard.owner'),
            'produksi' => view('dashboard.produksi'),
            'armada' => view('dashboard.armada'),
            default => abort(403),
        };
    }
}