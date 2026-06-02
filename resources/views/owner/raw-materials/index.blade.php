@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-end justify-between gap-6">

        <div>

            <h1 class="text-3xl font-bold text-[#2f2f2f]">
                Raw Materials
            </h1>

            <p class="text-[#8a8a8a] mt-1">
                Manage Kanawa Express raw materials.
            </p>

        </div>

        <div
            class="flex flex-col sm:flex-row
            flex-wrap items-stretch sm:items-center
            gap-2 lg:gap-3"
        >

            <!-- SEARCH -->
            <form
                method="GET"
                class="w-[140px] lg:w-[170px] xl:w-[200px]"
            >

                <!-- PRESERVE FILTER -->
                <input
                    type="hidden"
                    name="filter"
                    value="{{ $filter }}"
                >

                <div class="relative">

                    <input
                        type="text"
                        name="search"

                        value="{{ request('search') }}"

                        placeholder="Search materials..."

                        class="w-full h-11 rounded-2xl
                            border border-[#e8e8e5]
                            bg-white px-4 pr-10
                            text-sm text-[#2f2f2f]
                            placeholder:text-[#9a9a9a]
                            transition
                            focus:outline-none
                            focus:ring-2
                            focus:ring-[#2f2f2f]/10
                            focus:border-[#2f2f2f]/20"
                    >

                    <!-- ICON -->
                    <div
                        class="absolute inset-y-0 right-3
                            flex items-center
                            text-[#9a9a9a]"
                    >

                        <i
                            data-lucide="search"
                            class="w-4 h-4"
                        ></i>

                    </div>

                </div>

            </form>

            <!-- HEALTH FILTER -->
            <label
                class="inline-flex items-center shadow-sm gap-2
                    px-4 h-11 rounded-2xl
                    border border-[#e8e8e5]
                    bg-white
                    text-sm text-[#2f2f2f]
                    cursor-pointer
                    transition
                    hover:bg-[#f3f3f1]"
            >

                <input
                    type="checkbox"

                    onchange="
                        const url =
                            new window.URL(window.location.href);

                        if (this.checked) {

                            url.searchParams.set(
                                'attention',
                                '1'
                            );

                        } else {

                            url.searchParams.delete(
                                'attention'
                            );

                        }

                        window.location.href =
                            url.toString();
                    "

                    {{ request('attention') ? 'checked' : '' }}

                    class="rounded border-black/20
                        text-[#2f2f2f]
                        focus:ring-[#2f2f2f]/20"
                >

                <span>
                    Attention
                </span>

            </label>

            <!-- FILTER -->
            <form

                class="relative"

                method="GET"
            >

                <select
                    name="filter"
                    onchange="this.form.submit()"
                    class="h-11 rounded-2xl
                        transition duration-200

                        cursor-pointer

                        focus:outline-none
                        focus:ring-2
                        focus:ring-black/5
                        focus:border-[#d8d8d5]

                        border border-[#e8e8e5]
                        bg-white

                        hover:bg-[#f3f3f1]
                        hover:border-[#dcdcd8]

                        px-3 lg:px-4

                        text-sm text-[#2f2f2f]

                        w-[80px] lg:w-[100px] xl:w-auto
                        max-w-full

                        truncate

                        disabled:opacity-50
                        disabled:cursor-not-allowed"
                >

                    <option
                        value="active"
                        {{ $filter === 'active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        {{ $filter === 'inactive' ? 'selected' : '' }}
                    >
                        Archived
                    </option>

                    <option
                        value="all"
                        {{ $filter === 'all' ? 'selected' : '' }}
                    >
                        All
                    </option>

                </select>

            </form>

            <!-- ADD -->
            <a
                href="{{ route('owner.raw-materials.create') }}"
                class="inline-flex shrink-0 items-center
                    px-4 lg:px-5 h-11 rounded-2xl

                    max-w-[120px] lg:max-w-none

                    overflow-hidden whitespace-nowrap text-ellipsis

                    bg-[#2f2f2f] text-white font-medium
                    hover:opacity-90 transition duration-200"
            >
            <i
                    data-lucide="plus"
                    class="w-4 h-4"
                ></i> Add
            </a>

        </div>
        
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-3xl border border-[#e8e8e5] shadow-sm overflow-hidden">

        <!-- TABLE HEADER -->
        <div
            class="grid grid-cols-[minmax(220px,2.5fr)_minmax(100px,1fr)_minmax(120px,1fr)_110px_80px_32px]
                items-center px-4 lg:px-5 xl:px-6
                py-3 lg:py-3.5 xl:py-4
                bg-[#f3f3f1]
                text-sm font-medium text-[#8a8a8a]"
        >

            <div>
                Material
            </div>

            <div class="text-center">
                Category
            </div>

            <div class="text-center">
                Total Stock
            </div>

            <div class="text-center">
                Status
            </div>

            <div class="text-center">
                Active
            </div>

            <div></div>

        </div>

        <!-- TABLE BODY -->
        <div
            x-data="rawMaterialsTable"

            class="divide-y divide-[#efefec]"
        >

            @forelse($rawMaterials as $material)

                <div
                    x-data="{

                        open: JSON.parse(
                            localStorage.getItem(
                                'raw-material-{{ $material->id }}'
                            ) ?? 'false'
                        ),

                        visible: true,

                        processing: false,

                        removing: false,

                        active: {{ $material->is_active ? 'true' : 'false' }},

                        toggleOpen()
                        {
                            this.open = !this.open;

                            localStorage.setItem(
                                'raw-material-{{ $material->id }}',
                                JSON.stringify(this.open)
                            );
                        },

                        resetExpand()
                        {
                            this.open = false;

                            localStorage.removeItem(
                                'raw-material-{{ $material->id }}'
                            );
                        },


                        async toggle(id)
                        {
                            /*
                            |--------------------------------------------------------------------------
                            | PREVIOUS STATE
                            |--------------------------------------------------------------------------
                            */

                            let previous = this.active;

                            let previousOpen = this.open;

                            this.processing = true;

                            /*
                            |--------------------------------------------------------------------------
                            | TOGGLE VISUAL
                            |--------------------------------------------------------------------------
                            */

                            this.active = !this.active;

                            this.resetExpand();

                            /*
                            |--------------------------------------------------------------------------
                            | UPDATE DATABASE
                            |--------------------------------------------------------------------------
                            */

                            try {

                                const response = await fetch(
                                    `/owner/raw-materials/${id}/toggle-active`,
                                    {
                                        method: 'PATCH',

                                        headers: {

                                            'X-CSRF-TOKEN':
                                                document.querySelector(
                                                    'meta[name=csrf-token]'
                                                ).content,

                                            'Accept': 'application/json',
                                        }
                                    }
                                );

                                if (!response.ok) {
                                    throw new Error('Request failed');
                                }

                            } catch (error) {

                                /*
                                |--------------------------------------------------------------------------
                                | ROLLBACK VISUAL
                                |--------------------------------------------------------------------------
                                */

                                this.active = previous;
                                this.open = previousOpen;

                                /*
                                |--------------------------------------------------------------------------
                                | ERROR TOAST
                                |--------------------------------------------------------------------------
                                */

                                $store.toastManager.addErrorToast(
                                    'Unable to update material status.'
                                );

                                this.processing = false;

                                return;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | TOAST QUEUE
                            |--------------------------------------------------------------------------
                            */

                            $store.toastManager.addUndoToast(
                                id,
                                '{{ $material->name }}',
                                this.active ? 'activated' : 'archived',

                                async () => {

                                    /*
                                    |--------------------------------------------------------------------------
                                    | RESTORE VISUAL
                                    |--------------------------------------------------------------------------
                                    */

                                    this.active = previous;
                                    this.open = previousOpen;

                                    visibleCount++;

                                    this.visible = true;

                                    this.removing = false;

                                    this.resetExpand();
                                    /*
                                    |--------------------------------------------------------------------------
                                    | RESTORE DATABASE
                                    |--------------------------------------------------------------------------
                                    */

                                    try {

                                        const response = await fetch(
                                            `/owner/raw-materials/${id}/toggle-active`,
                                            {
                                                method: 'PATCH',

                                                headers: {

                                                    'X-CSRF-TOKEN':
                                                        document.querySelector(
                                                            'meta[name=csrf-token]'
                                                        ).content,

                                                    'Accept': 'application/json',
                                                }
                                            }
                                        );

                                        if (!response.ok) {
                                            throw new Error('Undo failed');
                                        }

                                    } catch (error) {

                                        this.active = !previous;

                                        $store.toastManager.addErrorToast(
                                            'Unable to restore material.'
                                        );

                                        this.processing = false;

                                        return;
                                    }
                                }
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | ACTIVE FILTER
                            |--------------------------------------------------------------------------
                            */

                            if (
                                '{{ $filter }}' === 'active' &&
                                !this.active
                            ) {

                                this.removing = true;

                                this.resetExpand();

                                visibleCount--;

                                setTimeout(() => {
                                    this.visible = false;
                                }, 220);
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | INACTIVE FILTER
                            |--------------------------------------------------------------------------
                            */

                            if (
                                '{{ $filter }}' === 'inactive' &&
                                this.active
                            ) {

                                this.removing = true;

                                this.resetExpand();

                                visibleCount--;

                                setTimeout(() => {
                                    this.visible = false;
                                }, 220);
                            }

                            this.processing = false;

                        }
                    }"

                    x-show="visible"
                    x-transition.opacity.duration.500ms
                    class="group"
                    data-material-row
                >

                    <!-- MAIN ROW -->
                    <div
                        @click="
                            if (active && !removing) {
                                toggleOpen()
                            }
                        "
                        class="grid grid-cols-[minmax(220px,2.5fr)_minmax(100px,1fr)_minmax(120px,1fr)_110px_80px_32px]
                        
                            items-center px-4 lg:px-5 xl:px-6
                            py-3 lg:py-3.5 xl:py-4
                            cursor-pointer
                            hover:bg-[#f5f5f3]
                            transition duration-200"
                    >

                        <!-- MATERIAL -->
                        <div class="flex items-center gap-4 min-w-0">

                            <!-- IMAGE -->
                            <div class="shrink-0">

                                @if($material->image)

                                    <img
                                        src="{{ asset('storage/' . $material->image) }}"
                                        alt="{{ $material->name }}"
                                        class="w-10 h-10 lg:w-11 lg:h-11 xl:w-12 xl:h-12 rounded-2xl object-cover border border-[#e8e8e5]"
                                    >

                                @else

                                    <div
                                        class="w-10 h-10 lg:w-11 lg:h-11 xl:w-12 xl:h-12 rounded-2xl
                                            bg-[#f1f1ef]
                                            border border-[#e8e8e5]"
                                    ></div>

                                @endif

                            </div>

                            <!-- NAME -->
                            <div class="min-w-0">

                                <div class="font-medium text-[#2f2f2f] truncate">

                                    {{ $material->name }}

                                </div>

                            </div>

                        </div>

                        <!-- CATEGORY -->
                        <div class="flex justify-center">

                            <span
                                class="inline-flex items-center
                                    px-2.5 h-7 rounded-full
                                    bg-[#f1f1ef]
                                    text-xs font-medium
                                    text-[#737373]"
                            >
                                {{ $material->category }}
                            </span>

                        </div>

                        <!-- TOTAL STOCK -->
                        <div class="font-medium flex justify-center text-[#2f2f2f]">

                            {{ formatDecimal($material->total_stock) }}

                            {{ $material->base_unit }}

                        </div>

                        <!-- STATUS -->
                        <div class="flex justify-center">

                            @php

                                $health = $material->stock_health;

                            @endphp

                            <span
                                @class([

                                    'inline-flex items-center gap-1.5 px-2.5 h-7 rounded-full
                                    text-xs font-medium',

                                    'bg-red-100 text-red-700' =>
                                        $health === 'critical',

                                    'bg-orange-100 text-orange-700' =>
                                        $health === 'warning',

                                    'bg-yellow-100 text-yellow-700' =>
                                        $health === 'caution',

                                    'bg-green-100 text-green-700' =>
                                        $health === 'healthy',

                                ])
                            >

                                <!-- DOT -->
                                <span
                                    @class([

                                        'w-2 h-2 rounded-full',

                                        'bg-red-500' =>
                                            $health === 'critical',

                                        'bg-orange-500' =>
                                            $health === 'warning',

                                        'bg-yellow-500' =>
                                            $health === 'caution',

                                        'bg-green-500' =>
                                            $health === 'healthy',

                                    ])
                                ></span>

                                {{ ucfirst($health) }}

                            </span>

                        </div>

                        <!-- ACTIVE TOGGLE -->
                        <div class="flex justify-center">

                            <button
                                type="button"

                                @click.stop="if (!processing) toggle({{ $material->id }})"

                                class="relative inline-flex h-7 w-12
                                    items-center rounded-full
                                    transition duration-300"

                                :class="{
                                    'bg-[#2f2f2f]': active,
                                    'bg-black/10': !active,
                                    'opacity-50 cursor-not-allowed': processing
                                }"
                            >

                                <!-- TOGGLE CIRCLE -->
                                <span
                                    class="inline-block h-5 w-5
                                        rounded-full bg-white
                                        transition duration-300"

                                    :class="active
                                        ? 'translate-x-6'
                                        : 'translate-x-1'"
                                ></span>

                            </button>

                        </div>

                        <!-- CHEVRON -->
                        <div
                            x-show="active"
                            class="flex justify-end"
                        >

                            <i
                                data-lucide="chevron-down"
                                class="w-4 h-4 lg:w-5 lg:h-5 text-[#8a8a8a]
                                    transition duration-300"
                                :class="{
                                    'rotate-180': open
                                }"
                            ></i>

                        </div>

                    </div>

                    <div x-show="active">

                        <!-- EXPAND DETAIL -->
                        <div
                            x-show="open"
                            x-collapse
                            class="bg-[#f6f6f4] border-t border-[#e8e8e5]"
                        >

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">

                                <!-- LEFT -->
                                <div class="space-y-5">

                                    <!-- OPENED STOCK -->
                                    <div>

                                        <div class="text-xs font-medium uppercase
                                            tracking-wide text-[#8a8a8a]"
                                        >
                                            Opened Stock
                                        </div>

                                        <div class="mt-1 text-md font-semibold
                                            tracking-tight text-[#2f2f2f]"
                                        >

                                            {{ formatDecimal($material->opened_stock) }}

                                            {{ $material->base_unit }}

                                        </div>

                                        <p class="text-xs text-[#8a8a8a] mt-1">
                                            Currently opened stock used for production.
                                        </p>

                                    </div>

                                    <!-- SEALED STOCK -->
                                    <div>

                                        <div class="text-xs font-medium uppercase
                                            tracking-wide text-[#8a8a8a]"
                                        >
                                            Sealed Stock
                                        </div>

                                        <div class="mt-1 text-md font-semibold
                                            tracking-tight text-[#2f2f2f]"
                                        >

                                            {{ number_format($material->sealed_stock, 0, ',', '.') }}

                                            {{ $material->purchase_unit }}

                                        </div>

                                        <p class="text-xs text-[#8a8a8a] mt-1">
                                            Unopened packages remaining.
                                        </p>

                                    </div>

                                </div>

                                <!-- RIGHT -->
                                <div class="space-y-5">

                                    <!-- PACKAGE SIZE -->
                                    <div>

                                        <div class="text-xs font-medium uppercase
                                            tracking-wide text-[#8a8a8a]"
                                        >
                                            Quantity / Package
                                        </div>

                                        <div class="mt-1 text-md font-semibold
                                            tracking-tight text-[#2f2f2f]"
                                        >

                                            {{ formatDecimal($material->conversion_value) }}

                                            {{ $material->base_unit }}

                                            /

                                            {{ $material->purchase_unit }}

                                        </div>

                                    </div>

                                    <!-- MINIMUM STOCK -->
                                    <div>

                                        <div class="text-xs font-medium uppercase
                                            tracking-wide text-[#8a8a8a]"
                                        >
                                            Minimum Stock
                                        </div>

                                        <div class="mt-1 text-md font-semibold
                                            tracking-tight text-[#2f2f2f]"
                                        >

                                            {{ formatDecimal($material->minimum_stock) }}

                                            {{ $material->base_unit }}

                                        </div>

                                    </div>

                                    <!-- LATEST PRICE -->
                                    <div>

                                        <div class="text-xs font-medium uppercase
                                            tracking-wide text-[#8a8a8a]"
                                        >
                                            Latest Price
                                        </div>

                                        <div class="mt-1 text-md font-semibold
                                            tracking-tight text-[#2f2f2f]"
                                        >

                                            @if($material->latest_price)
                                                
                                                Rp {{ number_format($material->latest_price, 0, ',', '.') }}
                                            
                                            @else
                                                <span class="text-amber-600">
                                                    Price not set
                                                </span>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- RECENT TRANSACTIONS -->
                            <div class="px-6 pb-6">

                                <div class="border-t border-[#e8e8e5] pt-6">

                                    <!-- HEADER -->
                                    <div class="flex items-center justify-between mb-4">

                                        <div>

                                            <h3 class="font-semibold text-[#2f2f2f]">
                                                Recent Activity
                                            </h3>

                                            <p class="text-xs font-medium uppercase
                                                tracking-wide text-[#8a8a8a] mt-1"
                                            >
                                                Latest inventory movements for this material.
                                            </p>

                                        </div>

                                    </div>

                                    <!-- TRANSACTION LIST -->
                                    <div class="space-y-3">

                                        @forelse($material->transactions as $transaction)

                                            <div
                                                class="flex items-center justify-between
                                                    rounded-2xl border border-black/5
                                                    transition duration-200
                                                    bg-white/80 backdrop-blur-sm
                                                    px-4 py-3
                                                    hover:bg-[#f7f7f5]
                                                    hover:border-[#dcdcd8]"
                                            >

                                                <!-- LEFT -->
                                                <div>

                                                    <!-- TYPE -->
                                                    <div class="font-semibold tracking-tight text-[#2f2f2f]">

                                                        {{ $transaction->type_label }}

                                                    </div>

                                                    <!-- DATE -->
                                                    <div class="text-xs text-[#8a8a8a] mt-1">

                                                        {{ $transaction->created_at->diffForHumans() }}

                                                        <span class="mx-1">•</span>

                                                        {{ $transaction->created_at->format('d M Y • H:i') }}

                                                        @if($transaction->notes)

                                                            <div class="mt-2">

                                                                <div
                                                                    class="text-xs text-[#8a8a8a] mt-1"
                                                                >
                                                                  Note : {{ $transaction->notes }}
                                                                </div>

                                                            </div>

                                                        @endif

                                                    </div>
                                                </div>

                                                <!-- RIGHT -->
                                                <div
                                                    x-data="{
                                                        editing: false,

                                                         error: false,

                                                        originalPrice:
                                                            '{{ rtrim(rtrim($transaction->unit_price, '0'), '.') }}',

                                                        price:
                                                            '{{ rtrim(rtrim($transaction->unit_price, '0'), '.') }}'
                                                    }"
                                                    class="text-right"
                                                >

                                                    <!-- DEFAULT INFO -->
                                                    <div x-show="!editing">

                                                        <!-- QUANTITY -->
                                                        <div class="font-medium text-[#2f2f2f]">

                                                            {{ formatDecimal($transaction->quantity) }}

                                                            {{ $material->base_unit }}

                                                        </div>

                                                        <!-- PRICE -->
                                                        @if($transaction->total_price)

                                                            <div class="text-xs text-[#8a8a8a] mt-1">

                                                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}

                                                            </div>

                                                        @endif

                                                        <!-- EDIT -->
                                                        @if($transaction->is_restock)

                                                            <button
                                                                type="button"

                                                                @click.stop="editing = true"

                                                                class="text-xs text-[#8a8a8a]
                                                                    underline underline-offset-2
                                                                    mt-1 hover:opacity-80 transition"
                                                            >
                                                                Edit Price
                                                            </button>

                                                        @endif

                                                    </div>

                                                    <!-- EDIT FORM -->
                                                    @if($transaction->is_restock)

                                                        <div x-show="editing">

                                                            <form
                                                                @click.stop

                                                                @submit="

                                                                    const value =
                                                                        Number(
                                                                            $event.target.unit_price.value
                                                                        );

                                                                    if (
                                                                        !value ||
                                                                        value <= 0
                                                                    ) {

                                                                        error = true;

                                                                        $event.preventDefault();

                                                                        return;
                                                                    }

                                                                    error = false;

                                                                    const content =
                                                                        document.getElementById(
                                                                            'main-content'
                                                                        );

                                                                    sessionStorage.setItem(
                                                                        'scroll-position',
                                                                        content?.scrollTop ?? 0
                                                                    );
                                                                "

                                                                action="{{ route(
                                                                    'owner.raw-materials.update-restock-price',
                                                                    $transaction
                                                                ) }}"

                                                                method="POST"

                                                                class="flex items-center gap-2"
                                                            >

                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="flex flex-col my-4 items-start">

                                                                    <div class="relative">

                                                                        <div
                                                                            class="absolute inset-y-0 left-0
                                                                                flex items-center
                                                                                pl-4
                                                                                text-sm text-[#8a8a8a]
                                                                                pointer-events-none"
                                                                        >
                                                                            Rp
                                                                        </div>

                                                                        <input
                                                                            type="number"
                                                                            step="1"
                                                                            min="0"
                                                                            name="unit_price"

                                                                            x-model="price"
                                                                            @input="error = false"

                                                                            class="w-full h-9 rounded-xl
                                                                                border border-[#e8e8e5]
                                                                                pl-10 pr-4
                                                                                px-3 text-sm text-[#2f2f2f]
                                                                                transition duration-200
                                                                                focus:outline-none
                                                                                focus:ring-2
                                                                                focus:ring-black/5
                                                                                focus:border-[#d8d8d5]"

                                                                            :class="{
                                                                                'border-rose-300': error
                                                                            }"
                                                                        >

                                                                        <div
                                                                            x-show="error"
                                                                            class="absolute left-0 top-full mt-1
                                                                                text-xs text-rose-600"
                                                                        >
                                                                            Price must be greater than 0.
                                                                        </div>

                                                                    </div>

                                                                </div>
            
                                                                <!-- SAVE -->
                                                                <button
                                                                    type="submit"

                                                                    class="h-9 px-3 rounded-xl
                                                                        bg-[#2f2f2f] text-white
                                                                        text-xs font-medium
                                                                        hover:opacity-90 transition duration-200"
                                                                >
                                                                    Save
                                                                </button>

                                                                <!-- CANCEL -->
                                                                <button
                                                                    type="button"

                                                                    @click="
                                                                        price = originalPrice;
                                                                        error = false;
                                                                        editing = false;
                                                                    "

                                                                    class="text-xs text-[#8a8a8a]
                                                                        hover:opacity-90 transition duration-200"
                                                                >
                                                                    Cancel
                                                                </button>

                                                            </form>

                                                        </div>

                                                    @endif

                                                </div>

                                            </div>

                                        @empty

                                            <div
                                                class="rounded-2xl border border-dashed
                                                    border-[#dcdcd8] px-4 py-6
                                                    text-center text-xs font-medium uppercase
                                                    tracking-wide text-[#8a8a8a]"
                                            >

                                                No inventory activity yet.

                                            </div>

                                        @endforelse

                                    </div>

                                </div>

                            </div>

                            <!-- ACTION BAR -->
                            <div
                                class="flex items-center justify-between
                                    px-6 py-4
                                    border-t border-black/5
                                    bg-white/70 backdrop-blur-sm"
                            >

                                <!-- HINT -->
                                <p class="text-xs text-[#8a8a8a]">
                                    Inventory actions for this material.
                                </p>

                                <!-- ACTIONS -->
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                                    <a
                                        href="{{ route('owner.raw-materials.edit', $material) }}"
                                        class="inline-flex items-center justify-center
                                            h-10 px-5 rounded-2xl
                                            border border-[#e8e8e5]
                                            bg-white text-[#2f2f2f]
                                            text-sm font-medium
                                            hover:bg-[#f3f3f1] transition duration-200"
                                    >
                                        Edit
                                    </a>
                                    
                                    <button
                                        type="button"

                                        @click="$dispatch(
                                            'open-adjustment-modal',
                                            {
                                                id: {{ $material->id }},
                                                name: '{{ addslashes($material->name) }}',
                                                stock: '{{ formatDecimal($material->opened_stock) }}',
                                                unit: '{{ $material->base_unit }}'
                                            }
                                        )"

                                        class="inline-flex items-center justify-center
                                            h-10 px-5 rounded-2xl
                                            border border-[#e8e8e5]
                                            bg-white text-[#2f2f2f]
                                            text-sm font-medium
                                            hover:bg-[#f3f3f1]
                                            transition duration-200"
                                    >
                                        Adjustment
                                    </button>
                                    
                                    <button
                                        type="button"

                                        @click="$dispatch(
                                            'open-restock-modal',
                                            {
                                                id: {{ $material->id }},

                                                name: '{{ addslashes($material->name) }}',

                                                totalStock: '{{ formatDecimal($material->total_stock) }}',

                                                sealedStock: '{{ formatDecimal($material->sealed_stock) }}',

                                                purchaseUnit: '{{ $material->purchase_unit }}',

                                                unit: '{{ $material->base_unit }}',

                                                latestPrice:
                                                    {{ $material->latest_price ?? 'null' }}
                                            }
                                        )"

                                        class="inline-flex items-center justify-center
                                            h-10 px-5 rounded-2xl
                                            bg-[#2f2f2f] text-white
                                            text-sm font-medium
                                            hover:opacity-90 transition duration-200"
                                    >
                                        Restock
                                    </button>

                                </div>
                            </div>
                            
                        </div>

                    </div>

                </div>

            @empty

            @endforelse

            <!-- REACTIVE EMPTY STATE -->
            <div
                x-show="visibleCount === 0"
                x-transition.opacity
                class="px-6 py-16 text-center"
            >

                @if($search)

                    <div class="text-sm font-medium text-[#2f2f2f]">
                        No materials found.
                    </div>

                    <p class="text-sm text-[#8a8a8a] mt-2">
                        Try searching with another keyword.
                    </p>

                @elseif($attention)

                    <div class="text-sm font-medium text-[#2f2f2f]">
                        No materials need attention.
                    </div>

                    <p class="text-sm text-[#8a8a8a] mt-2">
                        All materials are currently healthy.
                    </p>

                @elseif($filter === 'active')

                    <div class="text-sm font-medium text-[#2f2f2f]">
                        No active materials.
                    </div>

                    <p class="text-sm text-[#8a8a8a] mt-2">
                        Active materials will appear here.
                    </p>

                @elseif($filter === 'inactive')

                    <div class="text-sm font-medium text-[#2f2f2f]">
                        No archived materials.
                    </div>

                    <p class="text-sm text-[#8a8a8a] mt-2">
                        Archived materials will appear here.
                    </p>

                @else

                    <div class="text-sm font-medium text-[#2f2f2f]">
                        No materials available.
                    </div>

                    <p class="text-sm text-[#8a8a8a] mt-2">
                        Create your first raw material to get started.
                    </p>

                @endif

            </div>

        </div>

    </div>

    <x-modals.adjustment-modal />
    <x-modals.restock-modal />

</div>


@endsection