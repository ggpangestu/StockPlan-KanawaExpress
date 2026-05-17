@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-[#2c1f16]">
                Raw Materials
            </h1>

            <p class="text-[#5c4432] mt-1">
                Kelola bahan baku Kanawa Express.
            </p>

        </div>

        <div class="flex items-center gap-3">

            <!-- FILTER -->
            <form method="GET">

                <select
                    name="filter"
                    onchange="this.form.submit()"
                    class="h-11 rounded-2xl
                        border border-black/10
                        bg-white px-4
                        text-sm text-[#2c1f16]"
                >

                    <option
                        value="active"
                        {{ $filter === 'active' ? 'selected' : '' }}
                    >
                        Active Materials
                    </option>

                    <option
                        value="inactive"
                        {{ $filter === 'inactive' ? 'selected' : '' }}
                    >
                        Archived Materials
                    </option>

                    <option
                        value="all"
                        {{ $filter === 'all' ? 'selected' : '' }}
                    >
                        All Materials
                    </option>

                </select>

            </form>

            <!-- ADD -->
            <a
                href="{{ route('owner.raw-materials.create') }}"
                class="inline-flex items-center px-5 h-11 rounded-2xl
                    bg-[#2c1f16] text-white font-medium
                    hover:opacity-90 transition"
            >
                Add Material
            </a>

        </div>
        
    </div>

    <!-- SUCCESS -->
    @if(session('success'))

        <div class="rounded-2xl bg-green-100 text-green-700 px-4 py-3">

            {{ session('success') }}

        </div>

    @endif

    <!-- TABLE -->
    <div class="bg-white rounded-3xl border border-black/5 shadow-sm overflow-hidden">

        <!-- TABLE HEADER -->
        <div
            class="grid grid-cols-[2.5fr_1fr_1fr_120px_80px_40px]
                items-center px-6 py-4
                bg-black/[0.03]
                text-sm font-medium text-[#5c4432]"
        >

            <div>
                Material
            </div>

            <div>
                Category
            </div>

            <div>
                Total Stock
            </div>

            <div>
                Status
            </div>

            <div>
                Active
            </div>

            <div></div>

        </div>

        <!-- TABLE BODY -->
        <div
            x-data="rawMaterialsTable"

            class="divide-y divide-black/5"
        >

            @forelse($rawMaterials as $material)

                <div
                    x-data="{

                        open: false,

                        visible: true,

                        active: {{ $material->is_active ? 'true' : 'false' }},

                        async toggle(id)
                        {
                            /*
                            |--------------------------------------------------------------------------
                            | PREVIOUS STATE
                            |--------------------------------------------------------------------------
                            */

                            let previous = this.active;

                            /*
                            |--------------------------------------------------------------------------
                            | TOGGLE VISUAL
                            |--------------------------------------------------------------------------
                            */

                            this.active = !this.active;

                            /*
                            |--------------------------------------------------------------------------
                            | UPDATE DATABASE
                            |--------------------------------------------------------------------------
                            */

                            await fetch(
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

                            /*
                            |--------------------------------------------------------------------------
                            | TOAST QUEUE
                            |--------------------------------------------------------------------------
                            */

                            addToast(
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

                                    visibleCount++;

                                    this.visible = true;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | RESTORE DATABASE
                                    |--------------------------------------------------------------------------
                                    */

                                    await fetch(
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

                                visibleCount--;

                                this.visible = false;
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

                                visibleCount--;

                                this.visible = false;
                            }

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
                            if ('{{ $filter }}' !== 'inactive') {
                                open = !open
                            }
                        "
                        class="grid grid-cols-[2.5fr_1fr_1fr_120px_80px_40px]
                            items-center px-6 py-4
                            cursor-pointer
                            hover:bg-black/[0.02]
                            transition"
                    >

                        <!-- MATERIAL -->
                        <div class="flex items-center gap-4 min-w-0">

                            <!-- IMAGE -->
                            <div class="shrink-0">

                                @if($material->image)

                                    <img
                                        src="{{ asset('storage/' . $material->image) }}"
                                        alt="{{ $material->name }}"
                                        class="w-12 h-12 rounded-2xl object-cover border border-black/5"
                                    >

                                @else

                                    <div
                                        class="w-12 h-12 rounded-2xl
                                            bg-black/[0.04]
                                            border border-black/5"
                                    ></div>

                                @endif

                            </div>

                            <!-- NAME -->
                            <div class="min-w-0">

                                <div class="font-medium text-[#2c1f16] truncate">

                                    {{ $material->name }}

                                </div>

                            </div>

                        </div>

                        <!-- CATEGORY -->
                        <div class="text-[#5c4432]">

                            {{ $material->category }}

                        </div>

                        <!-- TOTAL STOCK -->
                        <div class="font-medium text-[#2c1f16]">

                            {{ number_format($material->total_stock, 0, ',', '.') }}

                            {{ $material->base_unit }}

                        </div>

                        <!-- STATUS -->
                        <div>

                            @if($material->total_stock <= $material->minimum_stock)

                                <span
                                    class="inline-flex items-center px-3 h-8 rounded-full
                                        bg-red-100 text-red-700
                                        text-xs font-medium"
                                >
                                    Low
                                </span>

                            @else

                                <span
                                    class="inline-flex items-center px-3 h-8 rounded-full
                                        bg-green-100 text-green-700
                                        text-xs font-medium"
                                >
                                    Safe
                                </span>

                            @endif

                        </div>

                        <!-- ACTIVE TOGGLE -->
                        <div class="flex justify-center">

                            <button
                                type="button"

                                @click.stop="toggle({{ $material->id }})"

                                class="relative inline-flex h-7 w-12
                                    items-center rounded-full
                                    transition duration-300"

                                :class="active
                                    ? 'bg-[#2c1f16]'
                                    : 'bg-black/10'"
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

                        @if($filter !== 'inactive')

                            <!-- CHEVRON -->
                            <div class="flex justify-end">

                                <i
                                    data-lucide="chevron-down"
                                    class="w-5 h-5 text-[#5c4432]
                                        transition duration-300"
                                    :class="{
                                        'rotate-180': open
                                    }"
                                ></i>

                            </div>

                        @else

                            <div></div>

                        @endif

                    </div>

                    @if($filter !== 'inactive')

                        <!-- EXPAND DETAIL -->
                        <div
                            x-show="open"
                            x-collapse
                            class="bg-black/[0.02] border-t border-black/5"
                        >

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">

                                <!-- LEFT -->
                                <div class="space-y-5">

                                    <!-- OPENED STOCK -->
                                    <div>

                                        <div class="text-sm text-[#5c4432]">
                                            Opened Stock
                                        </div>

                                        <div class="mt-1 font-medium text-[#2c1f16]">

                                            {{ number_format($material->opened_stock, 0, ',', '.') }}

                                            {{ $material->base_unit }}

                                        </div>

                                        <p class="text-xs text-[#5c4432] mt-1">
                                            Currently opened stock used for production.
                                        </p>

                                    </div>

                                    <!-- SEALED STOCK -->
                                    <div>

                                        <div class="text-sm text-[#5c4432]">
                                            Sealed Stock
                                        </div>

                                        <div class="mt-1 font-medium text-[#2c1f16]">

                                            {{ number_format($material->sealed_stock, 0, ',', '.') }}

                                            {{ $material->purchase_unit }}

                                        </div>

                                        <p class="text-xs text-[#5c4432] mt-1">
                                            Unopened packages remaining.
                                        </p>

                                    </div>

                                </div>

                                <!-- RIGHT -->
                                <div class="space-y-5">

                                    <!-- PACKAGE SIZE -->
                                    <div>

                                        <div class="text-sm text-[#5c4432]">
                                            Quantity / Package
                                        </div>

                                        <div class="mt-1 font-medium text-[#2c1f16]">

                                            {{ number_format($material->conversion_value, 0, ',', '.') }}

                                            {{ $material->base_unit }}

                                            /

                                            {{ $material->purchase_unit }}

                                        </div>

                                    </div>

                                    <!-- MINIMUM STOCK -->
                                    <div>

                                        <div class="text-sm text-[#5c4432]">
                                            Minimum Stock
                                        </div>

                                        <div class="mt-1 font-medium text-[#2c1f16]">

                                            {{ number_format($material->minimum_stock, 0, ',', '.') }}

                                            {{ $material->base_unit }}

                                        </div>

                                    </div>

                                    <!-- LATEST PRICE -->
                                    <div>

                                        <div class="text-sm text-[#5c4432]">
                                            Latest Price
                                        </div>

                                        <div class="mt-1 font-medium text-[#2c1f16]">

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

                                <div class="border-t border-black/5 pt-6">

                                    <!-- HEADER -->
                                    <div class="flex items-center justify-between mb-4">

                                        <div>

                                            <h3 class="font-semibold text-[#2c1f16]">
                                                Recent Activity
                                            </h3>

                                            <p class="text-sm text-[#5c4432] mt-1">
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
                                                    bg-white px-4 py-3"
                                            >

                                                <!-- LEFT -->
                                                <div>

                                                    <!-- TYPE -->
                                                    <div class="font-medium text-[#2c1f16]">

                                                        @switch($transaction->type)

                                                            @case('restock')

                                                                Restock

                                                                @break

                                                            @case('adjustment_add')

                                                                Adjustment Add

                                                                @break

                                                            @case('adjustment_reduce')

                                                                Adjustment Reduce

                                                                @break

                                                            @case('production_usage')

                                                                Production Usage

                                                                @break

                                                            @default

                                                                Activity

                                                        @endswitch

                                                    </div>

                                                    <!-- DATE -->
                                                    <div class="text-xs text-[#5c4432] mt-1">

                                                        {{ $transaction->created_at->diffForHumans() }}

                                                        <span class="mx-1">•</span>

                                                        {{ $transaction->created_at->format('d M Y • H:i') }}

                                                    </div>
                                                </div>

                                                <!-- RIGHT -->
                                                <div
                                                    x-data="{ editing: false }"
                                                    class="text-right"
                                                >

                                                    <!-- DEFAULT VIEW -->
                                                    <template x-if="!editing">

                                                        <div>

                                                            <!-- QUANTITY -->
                                                            <div class="font-medium text-[#2c1f16]">

                                                                {{ number_format($transaction->quantity, 0, ',', '.') }}

                                                                {{ $material->base_unit }}

                                                            </div>

                                                            <!-- PRICE -->
                                                            @if($transaction->total_price)

                                                                <div class="text-xs text-[#5c4432] mt-1">

                                                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}

                                                                </div>

                                                            @endif

                                                            <!-- EDIT ACTION -->
                                                            @if($transaction->type === 'restock')

                                                                <button
                                                                    @click="editing = true"
                                                                    type="button"
                                                                    class="text-xs text-[#5c4432]
                                                                        underline underline-offset-2
                                                                        mt-1 hover:opacity-80 transition"
                                                                >
                                                                    Edit Price
                                                                </button>

                                                            @endif

                                                        </div>

                                                    </template>

                                                    <!-- EDIT MODE -->
                                                    <template x-if="editing">

                                                        <form
                                                            action="{{ route(
                                                                'owner.raw-materials.update-restock-price',
                                                                $transaction
                                                            ) }}"
                                                            method="POST"
                                                            class="flex items-center gap-2 justify-end"
                                                        >

                                                            @csrf
                                                            @method('PATCH')

                                                            <input
                                                                type="number"
                                                                step="0.01"
                                                                name="unit_price"
                                                                placeholder="New price"
                                                                required
                                                                class="h-9 w-28 rounded-xl
                                                                    border border-black/10
                                                                    px-3 text-sm"
                                                            >

                                                            <!-- SAVE -->
                                                            <button
                                                                type="submit"
                                                                class="h-9 px-3 rounded-xl
                                                                    bg-[#2c1f16] text-white
                                                                    text-xs font-medium"
                                                            >
                                                                Save
                                                            </button>

                                                            <!-- CANCEL -->
                                                            <button
                                                                type="button"
                                                                @click="editing = false"
                                                                class="text-xs text-[#5c4432]
                                                                    hover:opacity-80 transition"
                                                            >
                                                                Cancel
                                                            </button>

                                                        </form>

                                                    </template>

                                                </div>

                                            </div>

                                        @empty

                                            <div
                                                class="rounded-2xl border border-dashed
                                                    border-black/10 px-4 py-6
                                                    text-center text-sm text-[#5c4432]"
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
                                    bg-white/40"
                            >

                                <!-- HINT -->
                                <p class="text-xs text-[#5c4432]">
                                    Inventory actions for this material.
                                </p>

                                <!-- ACTIONS -->
                                <div class="flex items-center gap-3">

                                    <a
                                        href="{{ route('owner.raw-materials.edit', $material) }}"
                                        class="inline-flex items-center justify-center
                                            h-10 px-5 rounded-2xl
                                            border border-black/10
                                            bg-white text-[#2c1f16]
                                            text-sm font-medium
                                            hover:bg-black/[0.03] transition"
                                    >
                                        Edit
                                    </a>
                                    
                                    <a
                                        href="{{ route('owner.raw-materials.adjustment', $material) }}"
                                        class="inline-flex items-center justify-center
                                            h-10 px-5 rounded-2xl
                                            border border-black/10
                                            bg-white text-[#2c1f16]
                                            text-sm font-medium
                                            hover:bg-black/[0.03] transition"
                                    >
                                        Adjustment
                                    </a>
                                    
                                    <a
                                        href="{{ route('owner.raw-materials.restock', $material) }}"
                                        class="inline-flex items-center justify-center
                                            h-10 px-5 rounded-2xl
                                            bg-[#2c1f16] text-white
                                            text-sm font-medium
                                            hover:opacity-90 transition"
                                    >
                                        Restock
                                    </a>

                                </div>
                            </div>
                        </div>

                    @endif

                </div>

            @empty

            @endforelse

            <!-- REACTIVE EMPTY STATE -->
            <div
                x-show="visibleCount === 0"
                x-transition.opacity
                class="px-6 py-16 text-center text-[#5c4432]"
            >

                @if($filter === 'active')

                    No active materials.

                @elseif($filter === 'inactive')

                    No archived materials.

                @else

                    No materials available.

                @endif

            </div>

            <!-- TOAST STACK -->
            <div

                class="fixed top-4 sm:top-6 left-1/2
                    -translate-x-1/2
                    z-50
                    flex flex-col gap-3
                    rounded-3xl
                    items-center"

            >

                <template x-for="toast in undoQueue" :key="toast.id">

                    <div
                        x-transition.opacity.duration.300ms

                        class="w-[92vw]
                            sm:w-[26rem]
                            md:w-[28rem]
                            max-w-md
                            rounded-3xl
                            border border-black/5
                            bg-white shadow-xl
                            px-5 py-4"
                    >

                        <!-- HEADER -->
                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <div class="font-semibold text-[#2c1f16]">
                                    <span
                                        x-text="
                                            toast.action === 'archived'
                                                ? 'Material Archived'
                                                : 'Material Activated'
                                        "
                                    ></span>
                                </div>

                                <div class="text-sm text-[#5c4432] mt-1">

                                    <span class="font-medium" x-text="toast.materialName"></span>

                                    <span
                                        x-text="
                                            toast.action === 'archived'
                                                ? ' moved to archive.'
                                                : ' restored to active materials.'
                                        "
                                    ></span>

                                </div>

                            </div>

                            <div
                                class="text-xs font-medium
                                    text-[#5c4432]"
                                x-text="toast.seconds + 's'"
                            ></div>

                        </div>

                        <!-- ACTION -->
                        <div class="mt-4 flex justify-end">

                            <button
                                @click="undoToast(toast)"

                                :disabled="toast.processing"

                                class="inline-flex items-center justify-center
                                    h-10 px-4 rounded-2xl
                                    bg-[#2c1f16] text-white
                                    text-sm font-medium
                                    hover:opacity-90 transition"

                                :class="{
                                    'opacity-50 cursor-not-allowed':
                                        toast.processing
                                }"
                            >
                                Undo
                            </button>

                        </div>

                    </div>

                </template>

            </div>

        </div>

    </div>

</div>

@endsection