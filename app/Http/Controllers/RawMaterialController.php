<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\RawMaterialTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use  Illuminate\Http\JsonResponse;


class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $filter = $request->get('filter', 'active');

        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $query = RawMaterial::with([

            'transactions' => function ($query) {

                $query->latest()->take(3);

            }

        ]);

        /*
        |--------------------------------------------------------------------------
        | FILTER LOGIC
        |--------------------------------------------------------------------------
        */

        if ($filter === 'active') {

            $query->where('is_active', true);

        }

        elseif ($filter === 'inactive') {

            $query->where('is_active', false);

        }

        /*
        |--------------------------------------------------------------------------
        | MATERIALS
        |--------------------------------------------------------------------------
        */

        $rawMaterials = $query
            ->latest()
            ->get();

        return view(
            'owner.raw-materials.index',
            compact(
                'rawMaterials',
                'filter'
            )
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('owner.raw-materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'purchase_unit' => 'required|string|max:50',
            'base_unit' => 'required|string|max:50',
            'conversion_value' => 'required|numeric|min:1',
            'minimum_stock' => 'required|numeric|min:0',
        ]);
            
        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {
                
            $validated['image'] = $request 
                ->file('image')
                ->store('raw-materials', 'public');
                
        }
            
        /*
        |--------------------------------------------------------------------------
        | Default Inventory State
        |--------------------------------------------------------------------------
            
        */
        $validated['sealed_stock'] = 0;
        $validated['opened_stock'] = 0;
            
        /*
        |--------------------------------------------------------------------------
        | System Data
        |--------------------------------------------------------------------------
        */
            
        $validated['created_by'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Create Material
        |--------------------------------------------------------------------------
        */
            
        RawMaterial::create($validated);
            
        return redirect()
            ->route('owner.raw-materials.index')
            ->with('success', 'Raw material created successfully.');
    }


    public function restock(RawMaterial $rawMaterial): View
    {
        return view(
            'owner.raw-materials.restock',
            compact('rawMaterial')
        );
    }


    public function storeRestock(
        Request $request,
        RawMaterial $rawMaterial
    ): RedirectResponse
    {
        $validated = $request->validate([

            'quantity' => 'required|integer|min:1',

            'latest_price' => 'nullable|numeric|min:0',

            'notes' => 'nullable|string|max:1000',

        ]);

        /*
        |--------------------------------------------------------------------------
        | FIRST PRICE VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            $rawMaterial->latest_price === null &&
            !$request->filled('latest_price')
        ) {

            throw ValidationException::withMessages([

                'latest_price' =>
                    'Latest price is required for the first restock.',

            ]);

        }

        DB::transaction(function () use (
            $validated,
            $rawMaterial,
            $request
        ) {

            /*
            |--------------------------------------------------------------------------
            | BEFORE STOCK
            |--------------------------------------------------------------------------
            */

            $beforeStock = $rawMaterial->total_stock;

            /*
            |--------------------------------------------------------------------------
            | RESTOCK QUANTITY
            |--------------------------------------------------------------------------
            | Input:
            | 5 pack
            |--------------------------------------------------------------------------
            */

            $restockQuantity = $validated['quantity'];

            /*
            |--------------------------------------------------------------------------
            | CONVERT TO BASE UNIT
            |--------------------------------------------------------------------------
            */

            $convertedQuantity =
                $rawMaterial->convertToBaseUnit(
                    $restockQuantity
                );

            /*
            |--------------------------------------------------------------------------
            | UPDATE SEALED STOCK
            |--------------------------------------------------------------------------
            */

            $rawMaterial->sealed_stock += $restockQuantity;

            /*
            |--------------------------------------------------------------------------
            | OPTIONAL PRICE UPDATE
            |--------------------------------------------------------------------------
            */

            if ($request->filled('latest_price')) {

                $rawMaterial->latest_price =
                    $validated['latest_price'];

            }

            $rawMaterial->save();

            /*
            |--------------------------------------------------------------------------
            | AFTER STOCK
            |--------------------------------------------------------------------------
            */

            $afterStock = $rawMaterial->fresh()->total_stock;

            
            /*
            |--------------------------------------------------------------------------
            | PRICE SNAPSHOT
            |--------------------------------------------------------------------------
            */
            
            $unitPrice =
            $validated['latest_price']
            ?? $rawMaterial->latest_price;
            
            $totalPrice = null;
            
            if ($unitPrice !== null) {
                
                $totalPrice =
                $restockQuantity * $unitPrice;
                
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE TRANSACTION
            |--------------------------------------------------------------------------
            */

            RawMaterialTransaction::create([

                'raw_material_id' => $rawMaterial->id,

                'type' => 'restock',

                'quantity' => $convertedQuantity,

                'purchase_quantity' => $restockQuantity,

                'before_stock' => $beforeStock,

                'after_stock' => $afterStock,

                'unit_price' => $unitPrice,

                'total_price' => $totalPrice,

                'notes' => $validated['notes'] ?? null,

                'created_by' => auth()->id(),

            ]);

        });

        return redirect()
            ->route('owner.raw-materials.index')
            ->with('success', 'Restock completed successfully.');
    }

    public function updateRestockPrice(
        Request $request,
        RawMaterialTransaction $transaction
    ): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | ONLY RESTOCK ALLOWED
        |--------------------------------------------------------------------------
        */

        if ($transaction->type !== 'restock') {

            abort(403);

        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'unit_price' =>
                'required|numeric|min:0',

        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE PRICE
        |--------------------------------------------------------------------------
        */

        $transaction->unit_price =
            $validated['unit_price'];

        /*
        |--------------------------------------------------------------------------
        | RECALCULATE TOTAL
        |--------------------------------------------------------------------------
        */

        $transaction->total_price =
            $transaction->purchase_quantity *
            $validated['unit_price'];

        $transaction->save();

        /*
        |--------------------------------------------------------------------------
        | UPDATE LATEST PRICE
        |--------------------------------------------------------------------------
        */

        $transaction->rawMaterial->update([

            'latest_price' =>
                $validated['unit_price'],

        ]);

        return back()->with(
            'success',
            'Restock price updated successfully.'
        );
    }

    public function adjustment(
        RawMaterial $rawMaterial
    ): View
    {
        return view(
            'owner.raw-materials.adjustment',
            compact('rawMaterial')
        );
    }
   
    public function storeAdjustment(
        Request $request,
        RawMaterial $rawMaterial
    ): RedirectResponse
    {
        $validated = $request->validate([

            'type' => [
                'required',
                Rule::in([
                    'adjustment_add',
                    'adjustment_reduce',
                ]),
            ],

            'quantity' => 'required|numeric|min:0.01',

            'notes' => 'nullable|string|max:1000',

        ]);

        DB::transaction(function () use (
            $validated,
            $rawMaterial
        ) {

            /*
            |--------------------------------------------------------------------------
            | BEFORE STOCK
            |--------------------------------------------------------------------------
            */

            $beforeStock = $rawMaterial->total_stock;

            /*
            |--------------------------------------------------------------------------
            | ADJUSTMENT QUANTITY
            |--------------------------------------------------------------------------
            */

            $quantity = $validated['quantity'];

            /*
            |--------------------------------------------------------------------------
            | HANDLE ADJUSTMENT
            |--------------------------------------------------------------------------
            */

            if ($validated['type'] === 'adjustment_add') {

                $rawMaterial->opened_stock += $quantity;

            } else {

                /*
                |--------------------------------------------------------------------------
                | PREVENT NEGATIVE STOCK
                |--------------------------------------------------------------------------
                */

                if ($rawMaterial->opened_stock < $quantity) {

                    throw ValidationException::withMessages([

                        'quantity' =>
                            'Opened stock is insufficient.',

                    ]);

                }

                $rawMaterial->opened_stock -= $quantity;

            }

            $rawMaterial->save();

            /*
            |--------------------------------------------------------------------------
            | AFTER STOCK
            |--------------------------------------------------------------------------
            */

            $afterStock = $rawMaterial->fresh()->total_stock;

            /*
            |--------------------------------------------------------------------------
            | CREATE TRANSACTION
            |--------------------------------------------------------------------------
            */

            RawMaterialTransaction::create([

                'raw_material_id' => $rawMaterial->id,

                'type' => $validated['type'],

                'quantity' => $quantity,

                'before_stock' => $beforeStock,

                'after_stock' => $afterStock,

                'unit_price' => null,

                'total_price' => null,

                'notes' => $validated['notes'] ?? null,

                'created_by' => auth()->id(),

            ]);
        });

        return redirect()
            ->route('owner.raw-materials.index')
            ->with(
                'success',
                'Stock adjustment completed successfully.'
            );
    }

    public function toggleActive(
    RawMaterial $rawMaterial
    ): JsonResponse
    {
        $rawMaterial->update([

            'is_active' => ! $rawMaterial->is_active,

        ]);

        return response()->json([

            'success' => true,

            'is_active' => $rawMaterial->is_active,

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(RawMaterial $rawMaterial): View
    {
        /*
        |--------------------------------------------------------------------------
        | HAS TRANSACTIONS
        |--------------------------------------------------------------------------
        */

        $hasTransactions =
            $rawMaterial->transactions()->exists();

        return view(
            'owner.raw-materials.edit',
            compact(
                'rawMaterial',
                'hasTransactions'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(
        Request $request,
        RawMaterial $rawMaterial
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'string',
                'max:255',
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'max:2048',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CONFIGURATION FIELDS
        |--------------------------------------------------------------------------
        */

        $hasTransactions =
            $rawMaterial->transactions()->exists();

        /*
        |--------------------------------------------------------------------------
        | ALLOW CONFIG UPDATE
        |--------------------------------------------------------------------------
        */

        if (!$hasTransactions) {

            $validated['base_unit'] =
                $request->base_unit;

            $validated['purchase_unit'] =
                $request->purchase_unit;

            $validated['conversion_value'] =
                $request->conversion_value;
        }

        /*
        |--------------------------------------------------------------------------
        | IMAGE UPDATE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $validated['image'] =
                $request->file('image')
                    ->store(
                        'raw-materials',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $rawMaterial->update($validated);

        return redirect()
            ->route('owner.raw-materials.index')
            ->with(
                'success',
                'Raw material updated successfully.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
