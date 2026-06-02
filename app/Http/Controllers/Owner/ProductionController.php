<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Menu;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->get('filter', 'all');

        // REVISI: Load ingredients agar kita bisa menghitung booking-an stok
        $query = Production::with(['items.menu.ingredients']);

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $productions = $query->orderByRaw("FIELD(status, 'processing', 'planned', 'completed', 'cancelled')")->orderBy('plan_date', 'asc')->get();

        /*
        |--------------------------------------------------------------------------
        | MENGHITUNG RESERVED STOCK (STOK YANG DIBOOKING)
        |--------------------------------------------------------------------------
        */
        $totalReserved = [];
        $productionReservations = []; // Menyimpan bookingan per-ID rencana (berguna saat fitur edit)

        // Kita hitung semua stok yang nyangkut di rencana 'planned' & 'processing'
        $activeProductions = Production::with(['items.menu.ingredients'])
            ->whereIn('status', ['planned', 'processing'])
            ->get();

        foreach ($activeProductions as $prod) {
            $productionReservations[$prod->id] = [];
            foreach ($prod->items as $item) {
                if ($item->menu) {
                    foreach ($item->menu->ingredients as $ing) {
                        $needed = $ing->pivot->quantity * $item->target_quantity;
                        $totalReserved[$ing->id] = ($totalReserved[$ing->id] ?? 0) + $needed;
                        $productionReservations[$prod->id][$ing->id] = ($productionReservations[$prod->id][$ing->id] ?? 0) + $needed;
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | MENGIRIM DATA MATERIAL (DENGAN AVAILABLE STOCK)
        |--------------------------------------------------------------------------
        */
        $menus = Menu::where('is_active', true)->with('ingredients')->get();
        
        $rawMaterials = RawMaterial::where('is_active', true)->get()->map(function($rm) use ($totalReserved) {
            $reserved = $totalReserved[$rm->id] ?? 0;
            return [
                'id' => $rm->id,
                'name' => $rm->name,
                'physical_stock' => $rm->total_stock, // Stok fisik asli
                'reserved_stock' => $reserved,        // Stok yang dibooking rencana lain
                'available_stock' => $rm->total_stock - $reserved, // Sisa stok BISA dipakai
                'unit' => $rm->base_unit
            ];
        })->keyBy('id');

        return view('owner.productions.index', compact('productions', 'filter', 'menus', 'rawMaterials', 'productionReservations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.target_quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $production = Production::create([
                'plan_date' => $validated['plan_date'],
                'notes' => $validated['notes'],
                'status' => 'planned',
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $production->items()->create([
                    'menu_id' => $item['menu_id'],
                    'target_quantity' => $item['target_quantity'],
                    'actual_quantity' => 0,
                ]);
            }
        });

        return redirect()->route('owner.productions.index')
            ->with('success', 'Rencana produksi berhasil dikirim ke tim dapur!');
    }

    public function update(Request $request, Production $production): RedirectResponse
    {
        if (!in_array($production->status, ['planned', 'cancelled'])) {
            return back()->with('error', 'Tidak dapat mengubah rencana yang sedang atau sudah dikerjakan!');
        }

        $validated = $request->validate([
            'plan_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.target_quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $request, $production) {
            $production->update([
                'plan_date' => $validated['plan_date'],
                'notes' => $validated['notes'],
            ]);

            $production->items()->delete();

            foreach ($request->items as $item) {
                $production->items()->create([
                    'menu_id' => $item['menu_id'],
                    'target_quantity' => $item['target_quantity'],
                    'actual_quantity' => 0,
                ]);
            }
        });

        return redirect()->route('owner.productions.index')
            ->with('success', 'Rencana produksi berhasil diperbarui!');
    }

    public function destroy(Production $production): RedirectResponse
    {
        if (in_array($production->status, ['processing', 'completed'])) {
            return back()->with('error', 'Hanya rencana berstatus Planned yang bisa dihapus!');
        }

        $production->delete();

        return redirect()->route('owner.productions.index')
            ->with('success', 'Rencana produksi berhasil dihapus!');
    }
}