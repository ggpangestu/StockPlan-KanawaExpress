<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ProductionReportController extends Controller
{
    public function index(
        Request $request
    ): View|JsonResponse
    {
        $availableYears =
            Production::query()
                ->selectRaw(
                    'YEAR(plan_date) as year'
                )
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year');

        $availableMonths =
            collect();

        $selectedYear =
            $request->year;

        $selectedMonth =
            $request->month;

        $baseQuery =
            Production::query()

                ->with([
                    'items.menu.ingredients',
                    'wastes.rawMaterial',
                    'creator',
                ]);

        if ($request->year) {

            $availableMonths =
                Production::query()

                    ->whereYear(
                        'plan_date',
                        $request->year
                    )

                    ->selectRaw(
                        'MONTH(plan_date) as month'
                    )

                    ->distinct()

                    ->orderBy('month')

                    ->pluck('month');

            $baseQuery->whereYear(
                'plan_date',
                $request->year
            );
        }

        if ($request->month) {

            $baseQuery->whereMonth(
                'plan_date',
                $request->month
            );

        }

        $productions =
            (clone $baseQuery)

                ->orderByDesc('plan_date')

                ->paginate(15)

                ->withQueryString();

        $totalProductionCost = 0;

        $completedProductions =
            (clone $baseQuery)

                ->where(
                    'status',
                    'completed'
                )

                ->get();

        $totalProductions =
            $completedProductions
                ->count();

        $totalPortions =
            $completedProductions

                ->flatMap(
                    fn ($production)
                        => $production->items
                )

                ->sum(
                    'target_quantity'
                );

        $totalWasteRecords =
            $completedProductions

                ->flatMap(
                    fn ($production)
                        => $production->wastes
                )

                ->count();

        $averageDailyProduction = 0;

        if ($completedProductions->isNotEmpty()) {

            $days =
                max(
                    1,
                    $completedProductions
                        ->pluck('plan_date')
                        ->unique()
                        ->count()
                );

            $averageDailyProduction =
                (int) round(
                    $totalPortions / $days
                );
        }

        $menuSummary = [];

        foreach ($completedProductions as $production) {

            foreach ($production->items as $item) {

                $menuId = $item->menu_id;

                if (! isset($menuSummary[$menuId])) {

                    $menuSummary[$menuId] = [
                        'name' => $item->menu->name,
                        'total' => 0,
                    ];
                }

                $menuSummary[$menuId]['total']
                    += $item->target_quantity;
            }
        }

        $topMenu =
            collect($menuSummary)
                ->sortByDesc('total')
                ->first();

        $materialSummary = [];

        foreach ($completedProductions as $production) {

            foreach ($production->items as $item) {

                foreach (
                    $item->menu->ingredients
                    as $ingredient
                ) {

                    $usage =
                        $ingredient->pivot->quantity
                        *
                        $item->target_quantity;

                    $id = $ingredient->id;

                    if (! isset($materialSummary[$id])) {

                        $materialSummary[$id] = [

                            'name'
                                => $ingredient->name,

                            'unit'
                                => $ingredient->base_unit,

                            'quantity'
                                => 0,
                        ];
                    }

                    $materialSummary[$id]['quantity']
                        += $usage;
                }
            }
        }

        $topMaterial =
            collect($materialSummary)
                ->sortByDesc('quantity')
                ->first();

        $wasteSummary = [];

        foreach ($completedProductions as $production) {

            foreach (
                $production->wastes
                as $waste
            ) {

                $id =
                    $waste->raw_material_id;

                if (! isset($wasteSummary[$id])) {

                    $wasteSummary[$id] = [

                        'name'
                            => $waste->rawMaterial->name,

                        'unit'
                            => $waste->rawMaterial->base_unit,

                        'quantity'
                            => 0,
                    ];
                }

                $wasteSummary[$id]['quantity']
                    += $waste->quantity;
            }
        }

        $topWasteMaterial =
            collect($wasteSummary)
                ->sortByDesc('quantity')
                ->first();


        $productionTrend = [];

        $trendDetails = [];

        if ($selectedYear && $selectedMonth) {

            $weeklyTrend = [

                '1-7' => 0,
                '8-14' => 0,
                '15-21' => 0,
                '22-28' => 0,
                '29-31' => 0,

            ];

            foreach ($completedProductions as $production) {

                $week =
                    (int) ceil(
                        $production
                            ->plan_date
                            ->day / 7
                    );

                $label = match ($week) {

                    1 => '1-7',
                    2 => '8-14',
                    3 => '15-21',
                    4 => '22-28',
                    default => '29-31',

                };

                logger([
                    'day'   => $production->plan_date->day,
                    'week'  => $week,
                    'label' => $label,
                ]);

                if (! isset($trendDetails[$label])) {

                    $trendDetails[$label] = [];
                }

                $portions =
                    $production
                        ->items
                        ->sum(
                            'target_quantity'
                        );

                $trendDetails[$label][] = [

                    'date' =>
                        $production
                            ->plan_date
                            ->format('d M'),

                    'portions' =>
                        $portions,

                ];

                $weeklyTrend[$label] +=
                    $portions;
            }

            foreach (
                $weeklyTrend
                as $label => $total
            ) {

                $productionTrend[] = [

                    'label' => $label,

                    'total' => $total,

                    'details' =>
                        $trendDetails[$label]
                        ?? [],
                ];
            }
        }

        elseif ($selectedYear) {

            $monthlyTrend = [

                'Jan' => 0,
                'Feb' => 0,
                'Mar' => 0,
                'Apr' => 0,
                'May' => 0,
                'Jun' => 0,
                'Jul' => 0,
                'Aug' => 0,
                'Sep' => 0,
                'Oct' => 0,
                'Nov' => 0,
                'Dec' => 0,

            ];

            foreach ($completedProductions as $production) {

                $monthLabel =
                    $production
                        ->plan_date
                        ->format('M');

                if (! isset($trendDetails[$monthLabel])) {

                    $trendDetails[$monthLabel] = [];
                }

                $portions =
                    $production
                        ->items
                        ->sum(
                            'target_quantity'
                        );

                $trendDetails[$monthLabel][] = [

                    'date' =>
                        $production
                            ->plan_date
                            ->format('d M'),

                    'portions' =>
                        $portions,

                ];

                $monthlyTrend[$monthLabel] +=
                    $portions;
            }

            foreach (
                $monthlyTrend
                as $label => $total
            ) {

                $productionTrend[] = [

                    'label' => $label,

                    'total' => $total,

                    'details' =>
                        $trendDetails[$label]
                        ?? [],
                ];
            }
        }
        else {

            $yearlyTrend = [];

            $yearlyMonthSummary = [];

            foreach ($completedProductions as $production) {

                $label =
                    $production
                        ->plan_date
                        ->format('Y');

                if (! isset($trendDetails[$label])) {

                    $trendDetails[$label] = [];
                }

                if (! isset($yearlyTrend[$label])) {

                    $yearlyTrend[$label] = 0;
                }

                $portions =
                    $production
                        ->items
                        ->sum(
                            'target_quantity'
                        );

                $monthName =
                    $production
                        ->plan_date
                        ->format('M');

                if (
                    ! isset(
                        $yearlyMonthSummary[$label]
                    )
                ) {

                    $yearlyMonthSummary[$label] = [];
                }

                if (
                    ! isset(
                        $yearlyMonthSummary[$label][$monthName]
                    )
                ) {

                    $yearlyMonthSummary[$label][$monthName] = 0;
                }

                $yearlyMonthSummary[$label][$monthName]
                    += $portions;

                $yearlyTrend[$label] +=
                    $portions;
            }

            ksort($yearlyTrend);
            ksort($yearlyMonthSummary);

            foreach (
                $yearlyMonthSummary
                as $year => $months
            ) {

                $monthOrder = [

                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec',

                ];

                uksort(
                    $months,
                    fn ($a, $b)
                        =>
                        array_search(
                            $a,
                            $monthOrder
                        )
                        <=>
                        array_search(
                            $b,
                            $monthOrder
                        )
                );

                foreach (
                    $months
                    as $month => $total
                ) {

                    $trendDetails[$year][] = [

                        'date' => "{$month}",

                        'portions' => $total,

                    ];
                }
            }

            foreach (
                $yearlyTrend
                as $label => $total
            ) {

                $productionTrend[] = [

                    'label' => $label,

                    'total' => $total,

                    'details' =>
                        $trendDetails[$label]
                        ?? [],
                ];
            }
        }

        if ($request->ajax()) {

            return response()->json([

                'table' => view(
                    'owner.reports.productions.partials.table',
                    compact(
                        'productions'
                    )
                )->render(),

                'kpis' => view(
                    'owner.reports.productions.partials.kpi-cards',
                    compact(
                        'totalProductions',
                        'totalPortions',
                        'totalWasteRecords',
                        'averageDailyProduction'
                    )
                )->render(),

                'insights' => view(
                    'owner.reports.productions.partials.insights',
                    compact(
                        'topMenu',
                        'topMaterial',
                        'topWasteMaterial'
                    )
                )->render(),

                'productionTrend' => $productionTrend,

                'availableMonths' =>
                    $availableMonths
                        ->values(),

            ]);

        }


        return view(
            'owner.reports.productions.index',
            compact(
                'availableYears',
                'availableMonths',

                'productions',

                'totalProductions',
                'totalPortions',
                'totalWasteRecords',
                'averageDailyProduction',

                'topMenu',
                'topMaterial',
                'topWasteMaterial',

                'productionTrend',
            )
        );
    }
}