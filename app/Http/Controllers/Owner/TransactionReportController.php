<?php

namespace App\Http\Controllers\Owner;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\RawMaterialTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Exports\TransactionReportExport;
use Maatwebsite\Excel\Facades\Excel;

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

        $availableYears =
        RawMaterialTransaction::query()

            ->selectRaw(
                'YEAR(created_at) as year'
            )

            ->distinct()

            ->orderByDesc(
                'year'
            )

            ->pluck(
                'year'
            );


        $availableMonths =
            collect();

        if ($request->year) {

            $availableMonths =
                RawMaterialTransaction::query()

                    ->whereYear(
                        'created_at',
                        $request->year
                    )

                    ->selectRaw(
                        'MONTH(created_at) as month'
                    )

                    ->distinct()

                    ->orderBy('month')

                    ->pluck('month');
        }

        $baseQuery =
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

            ->year(
                $request->year
            )

            ->month(
                $request->month
            );

        $transactions =
            (clone $baseQuery)
                ->latest()
                ->paginate(15)
                ->withQueryString();

        $totalTransactions =
            (clone $baseQuery)
                ->count();

        $totalRestocks =
            (clone $baseQuery)
                ->where(
                    'type',
                    'restock'
                )
                ->count();

        $totalAdjustments =
            (clone $baseQuery)
                ->whereIn(
                    'type',
                    [
                        'adjustment_add',
                        'adjustment_reduce',
                    ]
                )
                ->count();

        $totalPurchaseValue =
            (clone $baseQuery)
                ->where(
                    'type',
                    'restock'
                )
                ->sum('total_price');

        if (
            !$request->year
        ) {

            $purchaseTrend =

                RawMaterialTransaction::query()

                    ->where(
                        'type',
                        'restock'
                    )

                    ->selectRaw("
                        YEAR(created_at) as label,
                        SUM(total_price) as total
                    ")

                    ->groupByRaw(
                        'YEAR(created_at)'
                    )

                    ->orderByRaw(
                        'YEAR(created_at)'
                    )

                    ->get();

        } elseif (
            !$request->month
        ) {

            $purchaseTrend =

                RawMaterialTransaction::query()

                    ->where(
                        'type',
                        'restock'
                    )

                    ->whereYear(
                        'created_at',
                        $request->year
                    )

                    ->selectRaw("
                        MONTH(created_at) as sort_month,

                        DATE_FORMAT(
                            created_at,
                            '%b'
                        ) as label,

                        SUM(total_price) as total
                    ")

                    ->groupBy(
                        'sort_month',
                        'label'
                    )

                    ->orderBy(
                        'sort_month'
                    )

                    ->get();

        } else {

            $purchaseTrend =

                RawMaterialTransaction::query()

                    ->where(
                        'type',
                        'restock'
                    )

                    ->whereYear(
                        'created_at',
                        $request->year
                    )

                    ->whereMonth(
                        'created_at',
                        $request->month
                    )

                    ->selectRaw("
                        DAY(created_at) as sort_day,

                        DATE_FORMAT(
                            created_at,
                            '%d %b'
                        ) as label,

                        SUM(total_price) as total
                    ")

                    ->groupBy(
                        'sort_day',
                        'label'
                    )

                    ->orderBy(
                        'sort_day'
                    )

                    ->get();
        }

        if ($request->ajax()) {

            return response()->json([

                'table' => view(
                    'owner.reports.transactions.partials.table',
                    compact('transactions')
                )->render(),

                'kpis' => view(
                    'owner.reports.transactions.partials.kpi-cards',
                    compact(
                        'totalTransactions',
                        'totalRestocks',
                        'totalAdjustments',
                        'totalPurchaseValue'
                    )
                )->render(),

                'purchaseTrend' =>
                    $purchaseTrend,

                'availableMonths' =>
                    $availableMonths,

            ]);

        }

        return view(
            'owner.reports.transactions.index',
            compact(
                'totalTransactions',
                'totalRestocks',
                'totalAdjustments',
                'totalPurchaseValue',
                'transactions',
                'purchaseTrend',
                'availableYears',
                'availableMonths',
            )
        );
    }

    public function chart(
        Request $request
    ): JsonResponse
    {
        $availableMonths =
            collect();

        if ($request->year) {

            $availableMonths =
                RawMaterialTransaction::query()

                    ->whereYear(
                        'created_at',
                        $request->year
                    )

                    ->selectRaw(
                        'MONTH(created_at) as month'
                    )

                    ->distinct()

                    ->orderBy('month')

                    ->pluck('month');
        }

        if (!$request->year) {

            $purchaseTrend =

                RawMaterialTransaction::query()

                    ->where(
                        'type',
                        'restock'
                    )

                    ->selectRaw("
                        YEAR(created_at) as label,
                        SUM(total_price) as total
                    ")

                    ->groupByRaw(
                        'YEAR(created_at)'
                    )

                    ->orderByRaw(
                        'YEAR(created_at)'
                    )

                    ->get();

        } elseif (!$request->month) {

            $purchaseTrend =

                RawMaterialTransaction::query()

                    ->where(
                        'type',
                        'restock'
                    )

                    ->whereYear(
                        'created_at',
                        $request->year
                    )

                    ->selectRaw("
                        MONTH(created_at) as sort_month,

                        DATE_FORMAT(
                            created_at,
                            '%b'
                        ) as label,

                        SUM(total_price) as total
                    ")

                    ->groupBy(
                        'sort_month',
                        'label'
                    )

                    ->orderBy(
                        'sort_month'
                    )

                    ->get();

        } else {

            $purchaseTrend =

                RawMaterialTransaction::query()

                    ->where(
                        'type',
                        'restock'
                    )

                    ->whereYear(
                        'created_at',
                        $request->year
                    )

                    ->whereMonth(
                        'created_at',
                        $request->month
                    )

                    ->selectRaw("
                        DAY(created_at) as sort_day,

                        DATE_FORMAT(
                            created_at,
                            '%d %b'
                        ) as label,

                        SUM(total_price) as total
                    ")

                    ->groupBy(
                        'sort_day',
                        'label'
                    )

                    ->orderBy(
                        'sort_day'
                    )

                    ->get();
        }

        return response()->json([

            'purchaseTrend' =>
                $purchaseTrend,

            'availableMonths' =>
                $availableMonths,

        ]);
    }

    public function composition(
        Request $request
    ): JsonResponse
    {
        $composition =
            RawMaterialTransaction::query()

                ->when(
                    $request->year,
                    fn ($query) =>
                        $query->whereYear(
                            'created_at',
                            $request->year
                        )
                )

                ->when(
                    $request->month,
                    fn ($query) =>
                        $query->whereMonth(
                            'created_at',
                            $request->month
                        )
                )

                ->selectRaw(
                    '
                    type,
                    COUNT(*) as total
                    '
                )

                ->groupBy('type')

                ->get()

                ->map(
                    function ($item) {

                        return [

                            'label' =>
                                match ($item->type) {

                                    'restock' =>
                                        'Restock',

                                    'adjustment_add' =>
                                        'Adjustment Add',

                                    'adjustment_reduce' =>
                                        'Adjustment Reduce',

                                    'production_usage' =>
                                        'Production Usage',

                                    default =>
                                        ucfirst(
                                            $item->type
                                        ),
                                },

                            'value' =>
                                (int) $item->total,

                        ];

                    }
                );

        return response()->json([

            'composition' =>
                $composition,

        ]);
    }

    public function export(
        Request $request
    )
    {
        if (
            $request->year &&
            $request->month
        ) {

            $fileName =
                'transaction-report-'
                . $request->year
                . '-'
                . str_pad(
                    $request->month,
                    2,
                    '0',
                    STR_PAD_LEFT
                )
                . '.xlsx';

        } elseif (
            $request->year
        ) {

            $fileName =
                'transaction-report-'
                . $request->year
                . '.xlsx';

        } else {

            $fileName =
                'transaction-report-all-years.xlsx';

        }

        return Excel::download(

            new TransactionReportExport(

                $request->search,

                $request->type,

                $request->year,

                $request->month

            ),

            $fileName

        );
    }

}