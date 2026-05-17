<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        // This looks for resources/views/production.blade.php
        return view('prod.production'); 
    }

    public function show($id)
    {
        // For now, we just return the detail view. 
        // Later, you'll use $id to fetch real data from the database.
        return view('prod.production-detail', compact('id'));
    }
}
