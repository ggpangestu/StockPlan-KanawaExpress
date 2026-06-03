@extends('layouts.app')

@section('content')

<div class="space-y-6">

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
        <button
            type="button"

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
            "
        >

            <span class="flex items-center gap-2">

                <i
                    data-lucide="download"
                    class="w-4 h-4"
                ></i>

                Export

            </span>

        </button>

    </div>

    <!-- SUMMARY -->
    <div
        class="
            grid
            grid-cols-1
            sm:grid-cols-2
            xl:grid-cols-4
            gap-4
        "
    >

        <!-- TRANSACTIONS -->
        <div
            class="
                bg-white
                border border-[#e8e8e5]
                rounded-3xl
                p-4

                hover:shadow-md

                transition
            "
        >

            <div
                class="
                    w-11 h-11
                    rounded-2xl

                    bg-emerald-50

                    flex items-center justify-center
                "
            >

                <i
                    data-lucide="activity"
                    class="w-5 h-5 text-emerald-500"
                ></i>

            </div>

            <p
                class="
                    mt-3
                    text-sm
                    text-[#8a8a8a]
                "
            >
                Transactions
            </p>

            <p
                class="
                    mt-1
                    text-3xl
                    font-semibold
                    text-[#2f2f2f]
                "
            >
                {{ number_format($totalTransactions) }}
            </p>

            <p
                class="
                    mt-2
                    text-xs
                    text-[#8a8a8a]
                "
            >
                Total recorded activities
            </p>

        </div>

        <!-- RESTOCK -->
        <div
            class="
                bg-white
                border border-[#e8e8e5]
                rounded-3xl
                p-4

                hover:shadow-md

                transition
            "
        >

            <div
                class="
                    w-11 h-11
                    rounded-2xl

                    bg-sky-50

                    flex items-center justify-center
                "
            >

                <i
                    data-lucide="package-plus"
                    class="w-5 h-5 text-sky-500"
                ></i>

            </div>

            <p class="mt-3 text-sm text-[#8a8a8a]">
                Restocks
            </p>

            <p class="mt-1 text-3xl font-semibold text-[#2f2f2f]">
                {{ number_format($totalRestocks) }}
            </p>

            <p class="mt-2 text-xs text-[#8a8a8a]">
                Inventory replenishments
            </p>

        </div>

        <!-- ADJUSTMENTS -->
        <div
            class="
                bg-white
                border border-[#e8e8e5]
                rounded-3xl
                p-4

                hover:shadow-md

                transition
            "
        >

            <div
                class="
                    w-11 h-11
                    rounded-2xl

                    bg-amber-50

                    flex items-center justify-center
                "
            >

                <i
                    data-lucide="scale"
                    class="w-5 h-5 text-amber-500"
                ></i>

            </div>

            <p class="mt-3 text-sm text-[#8a8a8a]">
                Adjustments
            </p>

            <p class="mt-1 text-3xl font-semibold text-[#2f2f2f]">
                {{ number_format($totalAdjustments) }}
            </p>

            <p class="mt-2 text-xs text-[#8a8a8a]">
                Stock corrections
            </p>

        </div>

        <!-- PURCHASE VALUE -->
        <div
            class="
                bg-white
                border border-[#e8e8e5]
                rounded-3xl
                p-4

                hover:shadow-md

                transition
            "
        >

            <div
                class="
                    w-11 h-11
                    rounded-2xl

                    bg-violet-50

                    flex items-center justify-center
                "
            >

                <i
                    data-lucide="banknote"
                    class="w-5 h-5 text-violet-500"
                ></i>

            </div>

            <p class="mt-3 text-sm text-[#8a8a8a]">
                Purchase Value
            </p>

            <p class="mt-1 text-3xl font-semibold text-[#2f2f2f]">
                Rp {{ number_format($totalPurchaseValue, 0, ',', '.') }}
            </p>

            <p class="mt-2 text-xs text-[#8a8a8a]">
                Total purchase amount
            </p>

        </div>

    </div>

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

                <h2
                    class="
                        font-semibold
                        text-[#2f2f2f]
                    "
                >
                    Monthly Purchase Value
                </h2>

                <p
                    class="
                        text-sm
                        text-[#8a8a8a]
                    "
                >
                    Purchase value trend over time.
                </p>

            </div>

        </div>

        <div
            class="
                h-80
                mt-6

                rounded-2xl

                border
                border-dashed
                border-[#e8e8e5]

                flex
                items-center
                justify-center

                text-sm
                text-[#8a8a8a]
            "
        >

            Chart Placeholder

        </div>

    </div>

    <div
        x-data="transactionReport()"
        x-init="init()"
    >

        <!-- FILTERS -->
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
                        xl:col-span-5
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
                            @selected(request('type') === 'restock')
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

                <!-- TIMEFRAME -->

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
                        Timeframe
                    </label>

                    <select
                        name="timeframe"
                        x-model="filters.timeframe"
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
                            All Time
                        </option>

                        <option
                            value="today"
                            {{-- @selected(request('timeframe') === 'today') --}}
                        >
                            Today
                        </option>

                        <option
                            value="past_7_days"
                            {{-- @selected(request('timeframe') === 'past_7_days') --}}
                        >
                            Past 7 Days
                        </option>

                        <option
                            value="past_30_days"
                            {{-- @selected(request('timeframe') === 'past_30_days') --}}
                        >
                            Past 30 Days
                        </option>

                        <option
                            value="this_month"
                            {{-- @selected(request('timeframe') === 'this_month') --}}
                        >
                            This Month
                        </option>

                    </select>

                </div>

                <!-- ACTIONS -->

                <div
                    class="
                        xl:col-span-3

                        flex
                        items-end
                        gap-3
                    "
                >

                    <button
                        type="button"

                        @click="
                            filters.search = '';
                            filters.type = '';
                            filters.timeframe = '';

                            fetchData();
                        "

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

        filters: {

            search: '{{ request('search') }}',

            type: '{{ request('type') }}',

            timeframe: '{{ request('timeframe') }}',

        },

        debounceTimer: null,

        init()
        {
            this.$watch(
                'filters',
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

                },
                {
                    deep: true
                }
            );
        },

        async fetchData()
        {
            const params =
                new URLSearchParams(
                    this.filters
                );

            const response =
                await fetch(
                    `{{ route('owner.reports.transactions') }}?${params}`,
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


            history.replaceState(
                {},
                '',
                `?${params}`
            );
        }

    };
}

</script>

@endpush

@endsection