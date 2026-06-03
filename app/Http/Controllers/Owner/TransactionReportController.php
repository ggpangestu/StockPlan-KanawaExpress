<?php

namespace App\Http\Controllers\Owner;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\RawMaterialTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransactionReportController extends Controller
{
    public function index(
        Request $request
    ): View|JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | SUMMARY CARDS
        |--------------------------------------------------------------------------
        */

        $transactions =
            RawMaterialTransaction::query()

            ->with([
                'rawMaterial',
                'creator',
            ])

            ->search(
                $request->search
            )

            ->type(
                $request->type
            )

            ->timeframe(
                $request->timeframe
            );

        $transactions =
            $transactions
                ->latest()
                ->paginate(15)
                ->withQueryString();

        $totalTransactions =
            RawMaterialTransaction::count();

        $totalRestocks =
            RawMaterialTransaction::where(
                'type',
                'restock'
            )->count();

        $totalAdjustments =
            RawMaterialTransaction::whereIn(
                'type',
                [
                    'adjustment_add',
                    'adjustment_reduce',
                ]
            )->count();

        $totalPurchaseValue =
            RawMaterialTransaction::where(
                'type',
                'restock'
            )->sum('total_price');

        
        if ($request->ajax()) {

            return response()->json([

                'table' => view(
                    'owner.reports.transactions.partials.table',
                    compact('transactions')
                )->render(),

            ]);

        }

        return view(
            'owner.reports.transactions.index',
            compact(
                'totalTransactions',
                'totalRestocks',
                'totalAdjustments',
                'totalPurchaseValue',
                'transactions'
            )
        );
    }
}