<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ArmadaController extends Controller
{
    public function index()
    {
        $armadas = User::where('role', 'armada')->latest()->get();

        return view('owner.armada.index', [
            'armadas' => $armadas
        ]);
    }

    public function create()
    {
        return view('owner.armada.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'armada',
        ]);

        return redirect()
            ->route('owner.armada.index')
            ->with('success', 'Armada berhasil ditambahkan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}