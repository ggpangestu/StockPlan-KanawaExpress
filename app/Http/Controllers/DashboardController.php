<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class DashboardController extends Controller
{
    public function index()
    {
        $role = request()->user()->role;

        return match ($role) {
            'owner' => view('dashboard.owner'),
            'produksi' => view('dashboard.produksi'),
            'armada' => view('dashboard.armada'),
            default => abort(403),
        };
    }
}