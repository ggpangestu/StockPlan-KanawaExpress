@extends('layouts.app')

@section('content')

<div
    x-data="transactionReport()"
    
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
            Transactions
        </span>

    </nav>

    <!-- HEADER -->
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
                    bg-violet-50
                    flex items-center justify-center
                    shrink-0
                "
            >

                <i
                    data-lucide="receipt-text"
                    class="w-7 h-7 text-violet-500"
                ></i>

            </div>

            <div>

                <div class="flex items-center gap-2 flex-wrap">

                    <h1
                        class="
                            text-3xl
                            font-bold
                            text-[#2f2f2f]
                        "
                    >
                        Transactions Report
                    </h1>

                    <span
                        class="
                            px-3 py-1
                            rounded-full
                            text-xs font-medium
                            bg-violet-50
                            text-violet-600
                        "
                    >
                        Purchase & Stock Activity
                    </span>

                </div>

                <p
                    class="
                        mt-2
                        text-[#8a8a8a]
                    "
                >
                    View, analyze, and export raw material
                    transaction history.
                </p>

            </div>

        </div>

        <!-- EXPORT -->
        <a
            :href="
                '{{ route('owner.reports.transactions.export') }}'
                +
                '?search=' + encodeURIComponent(filters.search)
                +
                '&type=' + encodeURIComponent(filters.type)
                +
                '&year=' + encodeURIComponent(filters.year)
                +
                '&month=' + encodeURIComponent(filters.month)
            "

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
                data-lucide="download"
                class="w-4 h-4"
            ></i>

            Export

        </a>

    </div>

    <div
        id="kpi-cards"
        x-ref="kpiCards"
    >

        @include(
            'owner.reports.transactions.partials.kpi-cards'
        )

    </div>

    <div>

        <!-- CHART -->
        <div
            class="
                bg-white
                border border-[#e8e8e5]
                rounded-3xl
                p-6
            "
        >

            <div
                class="
                    flex items-center gap-3
                "
            >

                <div
                    class="
                        w-10 h-10
                        rounded-2xl
                        bg-violet-50

                        flex items-center justify-center
                    "
                >

                    <i
                        data-lucide="chart-line"
                        class="w-5 h-5 text-violet-500"
                    ></i>

                </div>

                <div>

                    <div
                        class="
                            flex
                            items-center
                            justify-between
                        "
                    >

                        <h2
                            class="
                                text-lg
                                font-semibold
                            "
                            x-text="chartTitle"
                        >
                        </h2>

                    </div>

                    <p
                        class="
                            text-sm
                            text-[#8a8a8a]
                        "
                        x-text="chartDescription"
                    >
                    </p>

                </div>

                
            </div>
            
            <div
                class="
                    mt-6
                    transition-all
                    duration-500
                    ease-out
                "
                :class="
                    chartLoading
                        ? 'opacity-75 scale-[0.995]'
                        : 'opacity-100 scale-100'
                "
            >

                <div
                    x-ref="purchaseTrendChart"
                    x-show="hasChartData"
                    style="height:400px;"
                ></div>


                <div
                    x-show="!hasChartData"
                    class="
                        h-[400px]
                        flex
                        items-center
                        justify-center
                        text-sm
                        text-[#8a8a8a]
                        border
                        border-dashed
                        border-[#e8e8e5]
                        rounded-2xl
                    "
                >

                    No purchase data available for
                    the selected period.

                </div>

            </div>

        </div>

        <!-- FILTERS -->
        <div
            class="
                bg-white
                border border-[#e8e8e5]
                rounded-3xl
                p-6 mt-6
            "
        >

            <div
                class="
                    flex items-center gap-3
                "
            >

                <i
                    data-lucide="filter"
                    class="w-5 h-5 text-violet-500"
                ></i>

                <div>

                    <h2
                        class="
                            font-semibold
                            text-[#2f2f2f]
                        "
                    >
                        Filters
                    </h2>

                    <p
                        class="
                            text-sm
                            text-[#8a8a8a]
                        "
                    >
                        Filter transaction records.
                    </p>

                </div>

            </div>

            
            <form
                method="GET"
                action="{{ route('owner.reports.transactions') }}"
                class="
                    mt-6

                    grid
                    grid-cols-1

                    md:grid-cols-2

                    xl:grid-cols-12

                    gap-4
                "

            >

                <!-- SEARCH -->

                <div
                    class="
                        md:col-span-2
                        xl:col-span-4
                    "
                >

                    <label
                        class="
                            text-sm
                            font-medium
                            text-[#2f2f2f]
                        "
                    >
                        Search Material
                    </label>

                    <input
                        type="text"

                        name="search"
                        x-model="filters.search"

                        placeholder="Type material name to search..."

                        class="
                            w-full
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

                </div>

                <!-- TYPE -->

                <div
                    class="
                        xl:col-span-2
                    "
                >

                    <label
                        class="
                            text-sm
                            font-medium
                            text-[#2f2f2f]
                        "
                    >
                        Type
                    </label>

                    <select
                        name="type"
                        x-model="filters.type"
                        class="
                            w-full
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
                            All Types
                        </option>

                        <option
                            value="restock"
                            {{-- @selected(request('type') === 'restock') --}}
                        >
                            Restock
                        </option>

                        <option
                            value="adjustment_add"
                            {{-- @selected(request('type') === 'adjustment_add') --}}
                        >
                            Adjustment Add
                        </option>

                        <option
                            value="adjustment_reduce"
                            {{-- @selected(request('type') === 'adjustment_reduce') --}}
                        >
                            Adjustment Reduce
                        </option>

                        <option
                            value="production_usage"
                            {{-- @selected(request('type') === 'production_usage') --}}
                        >
                            Production Usage
                        </option>

                    </select>

                </div>

                
                <div class="xl:col-span-2">
                    
                    <label
                    class="
                    text-sm
                    font-medium
                    text-[#2f2f2f]
                    "
                    >
                        Year
                    </label>
                    
                    <select
                        name="year"
                        x-model="filters.year"
                        @change="handleYearChange()"
                        class="
                            w-full
                            mt-2

                            h-11
                            px-4

                            rounded-2xl

                            border border-[#e8e8e5]

                            disabled:bg-gray-100
                            disabled:text-gray-400

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
                        
                        <option value="{{ $year }}">
                            {{ $year }}
                        </option>
                        
                        @endforeach

                    </select>
                    
                </div>

                <div
                    class="
                        xl:col-span-2
                    "
                >

                    <label
                        class="
                            text-sm
                            font-medium
                            text-[#2f2f2f]
                        "
                    >
                        Month
                    </label>

                    <select

                        name="month"

                        x-model="filters.month"
                        @change="handleMonthChange()"

                        :disabled="!filters.year"

                        class="
                            w-full
                            mt-2

                            h-11
                            px-4

                            rounded-2xl

                            border border-[#e8e8e5]

                            disabled:bg-gray-100
                            disabled:text-gray-400

                            focus:outline-none
                            focus:ring-2
                            focus:ring-black/5
                            focus:border-[#2f2f2f]/10
                        "
                    >

                        <option value="">
                            All Months
                        </option>

                        <template
                            x-for="
                                month in availableMonths
                            "
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
                
                <!-- ACTIONS -->

                <div
                    class="
                        xl:col-span-2

                        flex
                        items-end
                        gap-3
                    "
                >

                    <button
                        type="button"

                        @click="resetFilters()"

                        class="
                            h-11

                            px-5

                            rounded-2xl

                            border border-[#e8e8e5]

                            text-sm
                            font-medium

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

            </form>

        </div>

        <!-- TABLE -->
        
        <div
            id="transactions-table"
            x-ref="tableContainer"
        >
            @include(
                'owner.reports.transactions.partials.table'
            )

        </div>

    </div>

    
</div>

@push('scripts')


<script>

function transactionReport()
{
    
    return {

        chart: null,

        purchaseTrend:
            @json($purchaseTrend),

        availableMonths:
            @json($availableMonths),

        filters: {

            search: '{{ request('search') }}',

            type: '{{ request('type') }}',

            year: '{{ request('year') }}',

            month: '{{ request('month') }}',

        },

        debounceTimer: null,
        loading: false,
        chartLoading: false,
        chartRequestId: 0,
        currentPageUrl: null,

        resetFilters()
        {
            this.filters.search = '';

            this.filters.type = '';

            this.filters.year = '';

            this.filters.month = '';

            this.fetchData();

            this.fetchChart();

            history.replaceState(
                {},
                '',
                '{{ route('owner.reports.transactions') }}'
            );
        },

        handleYearChange()
        {
            if (this.filters.month) {

                this.filters.month = '';

            }

            this.fetchData();

            this.fetchChart();
        },

        handleMonthChange()
        {
            this.fetchData();

            this.fetchChart();
        },

        get chartType()
        {
            return 'bar';
        },

        get chartTitle()
        {
            if (!this.filters.year) {

                return 'Purchase Value by Year';

            }

            if (!this.filters.month) {

                return `Purchase Value by Month (${this.filters.year})`;

            }

            const monthName =
                new Date(
                    this.filters.year,
                    this.filters.month - 1,
                    1
                ).toLocaleString(
                    'en-US',
                    {
                        month: 'long'
                    }
                );

            return `Purchase Value by Day (${monthName} ${this.filters.year})`;
        },

        get chartDescription()
        {
            if (!this.filters.year) {

                return 'Compare total purchase value across available years.';

            }

            if (!this.filters.month) {

                return 'Compare purchase value across months within the selected year.';

            }

            return 'Monitor daily purchase activity within the selected month.';
        },

        get hasChartData()
        {
            return this.purchaseTrend.length > 0;
        },

        renderChart()
        {

            const container =
                this.$refs.purchaseTrendChart;

            if (!container) {
                return;
            }

            if (
                container.offsetWidth === 0
            ) {

                requestAnimationFrame(() => {

                    this.renderChart();

                });

                return;
            }

            if (this.chart) {
                return;
            }

            this.chart =
                new window.ApexCharts(
                    container,
                    {
                        chart: {
                            type: this.chartType,
                            height: 350,

                            toolbar: {
                                show: false
                            },

                            animations: {

                                enabled: true,

                                easing: 'easeinout',

                                speed: 700,

                                animateGradually: {

                                    enabled: true,

                                    delay: 120

                                },

                                dynamicAnimation: {

                                    enabled: true,

                                    speed: 500

                                }

                            }

                        },

                        stroke: {
                            curve: 'smooth'
                        },

                        plotOptions: {

                            bar: {

                                columnWidth:
                                    this.purchaseTrend.length <= 1
                                        ? '8%'
                                        : this.purchaseTrend.length <= 3
                                            ? '20%'
                                            : '55%'

                            }

                        },

                        series: [
                            {
                                name: 'Purchase Value',
                                data:
                                    this.purchaseTrend.map(
                                        item =>
                                            Number(item.total)
                                    )
                            }
                        ],

                        dataLabels: {

                            enabled: false,

                            formatter: function(value)
                            {

                                return 'Rp ' +
                                    Number(value)
                                        .toLocaleString(
                                            'id-ID'
                                        );

                            }

                        },

                        xaxis: {
                            categories:
                                this.purchaseTrend.map(
                                    item =>
                                        item.label
                                )
                        },

                        tooltip: {

                            theme: 'light',

                            y: {

                                formatter: function(value)
                                {

                                    return 'Rp ' +
                                        Number(value)
                                            .toLocaleString(
                                                'id-ID'
                                            );

                                }

                            }

                        },

                        yaxis: {

                            title: {

                                text:
                                    'Purchase Value (Rp)'

                            },

                            labels: {

                                formatter: function(value)
                                {
                                    if (value >= 1000000) {

                                        return (
                                            value / 1000000
                                        ).toFixed(1) + 'M';

                                    }

                                    if (value >= 1000) {

                                        return (
                                            value / 1000
                                        ).toFixed(0) + 'K';

                                    }

                                    return String(value);
                                }

                            }

                        },
                    }
                );

            this.chart.render().then(() => {

                setTimeout(() => {

                    window.dispatchEvent(
                        new Event('resize')
                    );

                }, 50);

            });
        },

        updateChart()
        {

            if (this.chart) {

                this.chart.destroy();

                this.chart = null;

            }

            if (!this.hasChartData) {

                return;

            }

            this.$nextTick(() => {

                this.renderChart();

            });
        },

        init()
        {
            // this.$watch(
            //     'filters',
            //     () => {

            //         clearTimeout(
            //             this.debounceTimer
            //         );

            //         this.debounceTimer =
            //             setTimeout(
            //                 () => {

            //                     this.fetchData();

            //                 },
            //                 500
            //             );

            //     },
            //     {
            //         deep: true
            //     }
            // );

            this.$watch(
                'filters.search',
                () => {

                    clearTimeout(
                        this.debounceTimer
                    );

                    this.debounceTimer =
                        setTimeout(
                            () => {

                                this.fetchData();

                            },
                            500
                        );

                }
            );

            this.$watch(
                'filters.type',
                () => {

                    this.fetchData();

                }
            );

            this.$nextTick(() => {

                requestAnimationFrame(() => {

                    this.renderChart();

                });

            });

            this.paginationHandler = (event) => {

                const link =
                    event.target.closest(
                        '#transactions-table .pagination-link'
                    );

                if (!link) {
                    return;
                }

                event.preventDefault();

                this.goToPage(
                    link.href
                );

            };

            document.addEventListener(
                'click',
                this.paginationHandler
            );

        },

        async goToPage(url)
        {
            this.loading = true;

            try {

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

                this.$refs.tableContainer.innerHTML =
                    data.table;

                this.$refs.kpiCards.innerHTML =
                    data.kpis;

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

            } finally {

                this.loading = false;

            }
        },

        async fetchChart()
        {

            try {

                const requestId =
                    ++this.chartRequestId;

                this.chartLoading = true;

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

                const response =
                    await fetch(
                        `{{ route('owner.reports.transactions.chart') }}?${params.toString()}`,
                        {
                            headers: {
                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }
                        }
                    );

                const data =
                    await response.json();

                if (
                    requestId !==
                    this.chartRequestId
                ) {

                    return;

                }

                this.purchaseTrend =
                    data.purchaseTrend;

                this.availableMonths =
                    data.availableMonths;

                this.updateChart();

            } catch (error) {

                console.error(error);

            }finally {

                this.chartLoading = false;

            }
        },

        async fetchData()
        {
            this.loading = true;

            try {

                const params =
                    new URLSearchParams();

                Object.entries(
                    this.filters
                ).forEach(([key, value]) => {

                    if (value) {

                        params.append(
                            key,
                            value
                        );

                    }

                });

                const queryString =
                    params.toString();

                const url =
                    queryString
                        ? `{{ route('owner.reports.transactions') }}?${queryString}`
                        : `{{ route('owner.reports.transactions') }}`;

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

                this.$refs.tableContainer.innerHTML =
                    data.table;

                this.$refs.kpiCards.innerHTML =
                    data.kpis;

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

            } finally {

                this.loading = false;

            }
        },

    };
}

</script>

@endpush

@endsection