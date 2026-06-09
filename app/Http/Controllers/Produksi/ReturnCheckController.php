<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\FinishedGood;
use App\Models\ReturnCheck;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReturnCheckController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->get('filter', ReturnCheck::STATUS_PENDING);

        $query = ReturnCheck::with([
            'armada',
            'checker',
            'finishedGood.menu',
            'sessionItem.session',
            'rejectedFinishedGood',
        ])->latest();

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $returnChecks = $query->get();

        $stats = [
            'pending' => ReturnCheck::where('status', ReturnCheck::STATUS_PENDING)->sum('quantity'),
            'ready' => ReturnCheck::where('status', ReturnCheck::STATUS_READY)->sum('quantity'),
            'expired_damaged' => ReturnCheck::where('status', ReturnCheck::STATUS_EXPIRED_DAMAGED)->sum('quantity'),
        ];

        return view(
            'prod.returns.index',
            compact('returnChecks', 'filter', 'stats')
        );
    }

    public function update(Request $request, ReturnCheck $returnCheck): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    ReturnCheck::STATUS_READY,
                    ReturnCheck::STATUS_EXPIRED_DAMAGED,
                ]),
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($returnCheck->status !== ReturnCheck::STATUS_PENDING) {
            return back()->with('error', 'Returned product has already been checked.');
        }

        DB::transaction(function () use ($returnCheck, $validated) {
            $returnCheck = ReturnCheck::whereKey($returnCheck->id)
                ->lockForUpdate()
                ->firstOrFail();

            $finishedGood = FinishedGood::whereKey($returnCheck->finished_good_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['status'] === ReturnCheck::STATUS_READY) {
                if ($finishedGood->expired_date->lt(today())) {
                    throw ValidationException::withMessages([
                        'status' => 'This batch is already expired and cannot be returned to Ready stock.',
                    ]);
                }

                $finishedGood->current_quantity += $returnCheck->quantity;
                $finishedGood->status = FinishedGood::STATUS_AVAILABLE;
                $finishedGood->save();
            }

            if ($validated['status'] === ReturnCheck::STATUS_EXPIRED_DAMAGED) {
                $rejectedFinishedGood = FinishedGood::create([
                    'menu_id' => $finishedGood->menu_id,
                    'production_id' => $finishedGood->production_id,
                    'initial_quantity' => $returnCheck->quantity,
                    'current_quantity' => $returnCheck->quantity,
                    'production_date' => $finishedGood->production_date,
                    'expired_date' => $finishedGood->expired_date,
                    'status' => FinishedGood::STATUS_EXPIRED_DAMAGED,
                ]);

                $returnCheck->rejected_finished_good_id = $rejectedFinishedGood->id;
            }

            $returnCheck->status = $validated['status'];
            $returnCheck->notes = $validated['notes'] ?? null;
            $returnCheck->checked_by = Auth::id();
            $returnCheck->checked_at = now();
            $returnCheck->save();
        });

        return redirect()
            ->route('produksi.returns.index')
            ->with('success', 'Returned product has been checked.');
    }

    public function dispose(ReturnCheck $returnCheck): RedirectResponse
    {
        if (
            $returnCheck->status !== ReturnCheck::STATUS_EXPIRED_DAMAGED ||
            $returnCheck->rejected_finished_good_id === null
        ) {
            abort(403);
        }

        DB::transaction(function () use ($returnCheck) {
            $finishedGood = FinishedGood::whereKey($returnCheck->rejected_finished_good_id)
                ->lockForUpdate()
                ->firstOrFail();

            $finishedGood->update([
                'current_quantity' => 0,
                'status' => FinishedGood::STATUS_EMPTY,
            ]);
        });

        return back()->with('success', 'Expired / damaged product has been disposed.');
    }
}
