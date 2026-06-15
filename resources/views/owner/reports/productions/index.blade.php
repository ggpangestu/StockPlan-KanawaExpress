@extends('layouts.app')

@section('content')

<div
    x-data="productionReport()"
    class="space-y-6"
>

    <!-- BREADCRUMB -->
    <nav class="flex items-center gap-2 text-sm">

        <span class="text-[#8a8a8a]">
            Reports
        </span>

        <i
            data-lucide="chevron-right"
            class="w-4 h-4 text-[#c4c4c4]"
        ></i>

        <span class="font-medium text-[#2f2f2f]">
            Productions
        </span>

    </nav>

    <!-- PAGE HEADER -->
    <div
        class="
            flex flex-col gap-5
            lg:flex-row
            lg:items-center
            lg:justify-between
        "
    >

        <div class="flex items-start gap-4">

            <div
                class="
                    w-14 h-14
                    rounded-3xl
                    bg-emerald-50

                    flex
                    items-center
                    justify-center

                    shrink-0
                "
            >

                <i
                    data-lucide="factory"
                    class="
                        w-7 h-7
                        text-emerald-500
                    "
                ></i>

            </div>

            <div>

                <div
                    class="
                        flex
                        items-center
                        gap-2
                        flex-wrap
                    "
                >

                    <h1
                        class="
                            text-3xl
                            font-bold
                            text-[#2f2f2f]
                        "
                    >
                        Production Report
                    </h1>

                    <span
                        class="
                            px-3 py-1

                            rounded-full

                            text-xs
                            font-medium

                            bg-emerald-50
                            text-emerald-600
                        "
                    >
                        Production & Consumption
                    </span>

                </div>

                <p
                    class="
                        mt-2
                        text-[#8a8a8a]
                    "
                >
                    Monitor production activities,
                    material consumption,
                    and manufacturing performance.
                </p>

            </div>

        </div>

        <div
            class="
                flex
                items-end
                gap-3
                flex-wrap
                shrink-0
            "
        >

            <div>

                <select
                    name="year"
                    x-model="filters.year"
                    @change="handleYearChange()"
                    class="
                        w-40
                        mt-2

                        h-11
                        px-4

                        rounded-2xl

                        border border-[#e8e8e5]

                        focus:outline-none
                        focus:ring-2
                        focus:ring-black/5
                        focus:border-[#2f2f2f]/10
                    "
                >

                    <option value="">
                        All Years
                    </option>

                    @foreach($availableYears as $year)

                        <option
                            value="{{ $year }}"
                            @selected(request('year') == $year)
                        >
                            {{ $year }}
                        </option>

                    @endforeach

                </select>
            </div>

            <div>

                <select
                    name="month"

                    x-model="filters.month"

                    :disabled="!filters.year"

                    @change="handleMonthChange()"
                    class="
                        w-44
                        mt-2

                        h-11
                        px-4

                        rounded-2xl

                        border border-[#e8e8e5]

                        focus:outline-none
                        focus:ring-2
                        focus:ring-black/5
                        focus:border-[#2f2f2f]/10
                        disabled:bg-gray-100
                        disabled:text-gray-400
                    "
                >

                    <option value="">
                        All Months
                    </option>

                    <template
                        x-for="month in availableMonths"
                        :key="month"
                    >

                        <option
                            :value="month"

                            x-text="
                                new Date(
                                    2025,
                                    month - 1,
                                    1
                                ).toLocaleString(
                                    'en-US',
                                    {
                                        month: 'long'
                                    }
                                )
                            "
                        ></option>

                    </template>

                </select>
            </div>

            <!-- RESET -->
            <button
                type="button"
                @click="resetFilters()"
                class="
                    h-11
                    px-5

                    rounded-2xl

                    border border-[#e8e8e5]

                    bg-white

                    text-sm font-medium
                    text-[#2f2f2f]

                    hover:bg-[#f8f8f8]

                    transition

                    inline-flex
                    items-center
                    gap-2
                "
            >

                <i
                    data-lucide="rotate-ccw"
                    class="w-4 h-4"
                ></i>

                Reset

            </button>

        </div>

    </div>

    <div
        id="production-kpis"
        x-ref="kpiContainer"
    >

        @include(
            'owner.reports.productions.partials.kpi-cards'
        )

    </div>

    <div
        id="production-trend"
        x-ref="trendContainer"
    >

        @include(
            'owner.reports.productions.partials.production-trend'
        )

    </div>

    <div
        id="production-insights"
        x-ref="insightContainer"
    >

        @include(
            'owner.reports.productions.partials.insights'
        )

    </div>

    <div
        id="production-table"
        x-ref="tableContainer"
    >

        @include(
            'owner.reports.productions.partials.table'
        )

    </div>

</div>

@push('scripts')

<script>

function productionReport()
{
    return {

        trendChart: null,

        productionTrend:
            @json($productionTrend),

        availableMonths:
            @json($availableMonths),

        filters: {

            year:
                '{{ request('year') }}',

            month:
                '{{ request('month') }}',

        },

        handleYearChange()
        {
            if (!this.filters.year) {

                this.filters.month = '';

            }

            this.fetchData();
        },

        handleMonthChange()
        {
            this.fetchData();
        },

        get hasTrendData()
        {
            return this.productionTrend.length > 0;
        },

        async fetchData()
        {
            try {

                const params =
                    new URLSearchParams();

                if (this.filters.year) {

                    params.append(
                        'year',
                        this.filters.year
                    );

                }

                if (this.filters.month) {

                    params.append(
                        'month',
                        this.filters.month
                    );

                }

                const queryString =
                    params.toString();

                const url =
                    queryString
                        ? `{{ route('owner.reports.productions') }}?${queryString}`
                        : `{{ route('owner.reports.productions') }}`;

                const response =
                    await fetch(
                        url,
                        {
                            headers: {
                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }
                        }
                    );

                const data =
                    await response.json();

                this.availableMonths =
                    data.availableMonths;

                if (
                    this.filters.month &&
                    !this.availableMonths.includes(
                        Number(this.filters.month)
                    )
                ) {

                    this.filters.month = '';
                }

                this.$refs.tableContainer.innerHTML =
                    data.table;

                this.$refs.kpiContainer.innerHTML =
                    data.kpis;

                this.$refs.insightContainer.innerHTML =
                    data.insights;

                this.productionTrend =
                    data.productionTrend;

                this.updateTrendChart();

                createIcons({
                    icons
                });

                history.replaceState(
                    {},
                    '',
                    url
                );

            } catch (error) {

                console.error(error);

            }
        },

        resetFilters()
        {
            this.filters.year = '';

            this.filters.month = '';

            // this.availableMonths = [];

            history.replaceState(
                {},
                '',
                '{{ route('owner.reports.productions') }}'
            );

            this.fetchData();
        },

        init()
        {
            this.$nextTick(() => {

                setTimeout(() => {

                    this.renderTrendChart();

                }, 150);

            });
        },

        buildTrendChartOptions()
        {

            let chartTitle =
                'Production Trend';

            if (
                this.filters.year &&
                this.filters.month
            ) {

                chartTitle =
                    'Weekly Production Trend';

            }
            else if (
                this.filters.year
            ) {

                chartTitle =
                    'Monthly Production Trend';

            }

            return {

                chart: {

                    type: 'line',

                    height: 340,

                    foreColor: '#666',

                    toolbar: {
                        show: false
                    },

                    animations: {

                        enabled: true,

                        easing: 'easeinout',

                        speed: 800

                    }

                },

                stroke: {

                    curve: 'smooth',

                    width: 3,

                    lineCap: 'round',

                },

                fill: {

                    type: 'gradient',

                    gradient: {

                        opacityFrom: 0.12,

                        opacityTo: 0,

                        stops: [0, 90, 100]

                    }

                },

                markers: {

                    size: 4,

                    strokeWidth: 2,

                    hover: {

                        size: 7

                    }

                },

                grid: {
                    padding: {
                        right: 20
                    }
                },

                dataLabels: {
                    enabled: false
                },

                title: {

                    text: chartTitle,

                    align: 'left',

                    style: {

                        fontSize: '14px',

                        fontWeight: 600,

                    }

                },

                subtitle: {

                    text:
                        'Based on completed productions',

                    style: {

                        fontSize: '12px',

                        color: '#888'

                    }

                },

                series: [

                    {

                        name:
                            'Produced Portions',

                        data:
                            this.productionTrend.map(
                                item =>
                                    Number(item.total)
                            )

                    }

                ],

                xaxis: {

                    categories:

                        this.productionTrend.map(
                            item =>
                                item.label
                        ),

                    labels: {

                        rotate: 0,

                        hideOverlappingLabels: false,

                    }

                },

                yaxis: {

                    title: {

                        text:
                            'Portions'

                    },

                    labels: {

                        formatter(value)
                        {
                            return Intl.NumberFormat()
                                .format(
                                    Math.round(value)
                                );
                        }

                    }

                },

                colors: [
                    'rgba(16,185,129,0.85)'
                ],

                tooltip: {

                    custom: ({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) => {

                        const trend =
                            this.productionTrend[
                                dataPointIndex
                            ];

                        let html = `

                            <div
                                style="
                                    padding:12px;
                                    min-width:220px;
                                "
                            >

                                <div
                                    style="
                                        font-weight:600;
                                        margin-bottom:8px;
                                    "
                                >

                                    ${trend.label}

                                </div>

                                <div
                                    style="
                                        margin-bottom:10px;
                                    "
                                >

                                    ${trend.total}
                                    Total Portions

                                    <br>

                                    <span
                                        style="
                                            color:#777;
                                            font-size:12px;
                                        "
                                    >

                                        ${trend.details?.length ?? 0}
                                        ${
                                            this.filters.year
                                                ? 'Plans'
                                                : 'Months'
                                        }

                                    </span>

                                    <hr
                                        style="
                                            margin:8px 0;
                                            border:none;
                                            border-top:1px solid #eee;
                                        "
                                    >

                                </div>

                        `;

                        if (
                            trend.details &&
                            trend.details.length
                        ) {

                            trend.details
                                .slice(0, 8)
                                .forEach(item => {

                                    html += `

                                        <div
                                            style="
                                                display:flex;
                                                justify-content:space-between;
                                                gap:16px;
                                                margin-top:4px;
                                            "
                                        >

                                            <span>
                                                ${item.date}
                                            </span>

                                            <span>
                                                ${item.portions}
                                                Portions
                                            </span>

                                        </div>

                                    `;
                                }
                            );
                        }

                        if (
                            trend.details &&
                            trend.details.length > 8
                        ) {

                            html += `

                                <div
                                    style="
                                        margin-top:8px;
                                        color:#888;
                                        font-size:12px;
                                    "
                                >

                                    + ${
                                        trend.details.length - 8
                                    } more records

                                </div>

                            `;
                        }

                        html += `</div>`;

                        return html;
                    }

                }

            };
        },

        renderTrendChart()
        {
            const container =
                this.$refs.productionTrendChart;

            if (!container) {
                return;
            }

            if (this.trendChart) {

                this.trendChart.destroy();

            }

            this.trendChart =
                new ApexCharts(
                    container,
                    this.buildTrendChartOptions()
                );

            this.trendChart.render();
        },

        updateTrendChart()
        {
            if (
                !this.hasTrendData
            ) {

                if (
                    this.trendChart
                ) {

                    this.trendChart.destroy();

                    this.trendChart = null;

                }

                return;
            }

            if (
                !this.trendChart
            ) {

                this.renderTrendChart();

                return;
            }

            this.trendChart.updateOptions(
                this.buildTrendChartOptions(),
                true,
                true
            );
        },

    };
}

</script>

@endpush

@endsection