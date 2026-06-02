<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\RawMaterial;

class MenuController extends Controller
{
    public function index(Request $request): View
{
    $filter = $request->get('filter', 'active');

    $query = Menu::with('ingredients');

    if ($filter === 'active') {
        $query->where('is_active', true);
    } elseif ($filter === 'inactive') {
        $query->where('is_active', false);
    }

    // Ambil data menu dan lakukan kalkulasi pintar
    $menus = $query->latest()->get()->map(function ($menu) {
        $hpp = 0;
        $isReady = true;

        foreach ($menu->ingredients as $ingredient) {
            /* * 1. KALKULASI HPP (Harga Pokok Penjualan)
             * Rumus: Harga Beli / Nilai Konversi = Harga Per Base Unit (Misal: per ml / per gram)
             * Cost = Harga Per Base Unit * Kuantitas Resep
             */
            $pricePerBaseUnit = ($ingredient->latest_price > 0 && $ingredient->conversion_value > 0)
                ? $ingredient->latest_price / $ingredient->conversion_value
                : 0;

            $ingredientCost = $ingredient->pivot->quantity * $pricePerBaseUnit;
            $hpp += $ingredientCost;

            /* * 2. CEK KESIAPAN STOK
             * Memanfaatkan fungsi getTotalStockAttribute() dari model RawMaterial Anda
             */
            if ($ingredient->total_stock < $ingredient->pivot->quantity) {
                $isReady = false; // Jika ada satu saja bahan yang kurang, menu tidak siap dibuat
            }
        }

        // Menyimpan hasil kalkulasi ke dalam object menu agar bisa dibaca di Blade
        $menu->hpp = $hpp;
        $menu->profit = $menu->price - $hpp;
        
        // Menghindari pembagian dengan 0 jika harga jual belum diatur
        $menu->margin_percentage = $menu->price > 0 ? ($menu->profit / $menu->price) * 100 : 0;
        
        $menu->is_ready = $isReady;

        return $menu;
    });

    return view('owner.menus.index', compact('menus', 'filter'));
}

    public function create(): View
    {
        // Ambil bahan baku yang aktif saja untuk dipilih di form
        $rawMaterials = RawMaterial::where('is_active', true)->get();
        return view('owner.menus.create', compact('rawMaterials'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            // Validasi Array Ingredients
            'ingredients' => 'required|array|min:1',
            'ingredients.*.raw_material_id' => 'required|exists:raw_materials,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
            'expires_in_days' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $request) {
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('menus', 'public');
            }
            
            $validated['created_by'] = auth()->id();
            $menu = Menu::create($validated);

            // Menyimpan Ingredients ke Pivot Table
            $ingredientsData = [];
            foreach ($request->ingredients as $ingredient) {
                $ingredientsData[$ingredient['raw_material_id']] = ['quantity' => $ingredient['quantity']];
            }
            $menu->ingredients()->attach($ingredientsData);
        });

        return redirect()->route('owner.menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu): View
    {
        $rawMaterials = RawMaterial::where('is_active', true)->get();
        // Load relasi agar bisa digunakan di form edit
        $menu->load('ingredients');
        return view('owner.menus.edit', compact('menu', 'rawMaterials'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.raw_material_id' => 'required|exists:raw_materials,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
            'expires_in_days' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $request, $menu) {
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('menus', 'public');
            }

            $menu->update($validated);

            // Sync Ingredients (Menghapus yang lama, mengganti dengan yang baru dari form)
            $ingredientsData = [];
            foreach ($request->ingredients as $ingredient) {
                $ingredientsData[$ingredient['raw_material_id']] = ['quantity' => $ingredient['quantity']];
            }
            $menu->ingredients()->sync($ingredientsData);
        });

        return redirect()->route('owner.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function toggleActive(Menu $menu): JsonResponse
    {
        $menu->update(['is_active' => !$menu->is_active]);
        return response()->json([
            'success' => true,
            'is_active' => $menu->is_active,
        ]);
    }
}
