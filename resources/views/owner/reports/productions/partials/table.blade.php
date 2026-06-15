<!-- TABLE -->
<div
    x-data="{
        expandedProductions: [],

        toggleProduction(id) {

            if (this.expandedProductions.includes(id)) {

                this.expandedProductions =
                    this.expandedProductions.filter(
                        item => item !== id
                    );

                return;
            }

            this.expandedProductions.push(id);
        }
    }"

    class="
        bg-white
        border border-[#e8e8e5]
        rounded-3xl
        overflow-hidden
        mt-6
    "
>

    <div
        class="
            px-6 py-5
            border-b border-[#e8e8e5]
        "
    >

        <div
            class="
                flex items-center gap-3
            "
        >

            <i
                data-lucide="table-2"
                class="w-5 h-5 text-emerald-500"
            ></i>

            <h2
                class="
                    font-semibold
                    text-[#2f2f2f]
                "
            >
                Production History
            </h2>

        </div>

    </div>

    @if($productions->isEmpty())

        <div
            class="
                h-80

                flex
                items-center
                justify-center

                text-sm
                text-[#8a8a8a]
            "
        >

            No production records found.

        </div>

    @else

        <div class="overflow-x-auto">

            <div class="min-w-[900px]">

                <!-- HEADER -->
                <div
                    class="
                        grid
                        grid-cols-[1.2fr_100px_120px_120px_140px_40px]
                        gap-x-24

                        px-5
                        py-4

                        bg-[#fafafa]
                        border-b border-[#e8e8e5]

                        text-xs
                        font-semibold
                        uppercase

                        text-[#8a8a8a]
                    "
                >

                    <div>Plan Date</div>

                    <div class="text-center">
                        Menus
                    </div>

                    <div class="text-center">
                        Portions
                    </div>

                    <div class="text-center">
                        Waste
                    </div>

                    <div class="text-center">
                        Status
                    </div>

                    <div></div>

                </div>

                <!-- ROWS -->
                <div>

                    @foreach($productions as $production)

                        @php

                            $statusClasses = match($production->status) {

                                'planned'
                                    => 'bg-slate-100 text-slate-700',

                                'processing'
                                    => 'bg-amber-50 text-amber-700',

                                'completed'
                                    => 'bg-emerald-50 text-emerald-700',

                                'cancelled'
                                    => 'bg-rose-50 text-rose-700',

                                default
                                    => 'bg-gray-100 text-gray-700',
                            };

                        @endphp

                        <!-- MAIN ROW -->
                        <div
                            @click="
                                toggleProduction({{ $production->id }})
                            "

                            class="
                                grid
                                grid-cols-[1.2fr_100px_120px_120px_140px_40px]
                                gap-x-24

                                items-center

                                px-5
                                py-4

                                border-b border-[#f3f3f1]

                                cursor-pointer

                                hover:bg-emerald-50/40

                                transition
                            "
                        >

                            <!-- PLAN DATE -->
                            <div>

                                <div
                                    class="
                                        text-sm
                                        font-medium
                                        text-[#2f2f2f]
                                    "
                                >
                                    {{ $production->plan_date->format('d M Y') }}
                                </div>

                                <div
                                    class="
                                        mt-1
                                        text-xs
                                        text-[#8a8a8a]
                                    "
                                >
                                    Production Plan
                                </div>

                            </div>

                            <!-- MENUS -->
                            <div
                                class="
                                    text-center
                                    font-semibold
                                    text-[#2f2f2f]
                                "
                            >
                                {{ $production->items->count() }}
                            </div>

                            <!-- PORTIONS -->
                            <div
                                class="
                                    text-center
                                    font-semibold
                                    text-[#2f2f2f]
                                "
                            >
                                {{ $production->items->sum('target_quantity') }}
                            </div>

                            <!-- WASTE -->
                            <div
                                class="
                                    text-center
                                    font-semibold
                                    text-[#2f2f2f]
                                "
                            >
                                {{ $production->wastes->count() }}
                            </div>

                            <!-- STATUS -->
                            <div class="text-center">

                                <span
                                    class="
                                        inline-flex
                                        items-center

                                        px-3 py-1

                                        rounded-full

                                        text-xs
                                        font-medium

                                        {{ $statusClasses }}
                                    "
                                >
                                    {{ ucfirst($production->status) }}
                                </span>

                            </div>

                            <!-- CHEVRON -->
                            <div
                                class="
                                    flex
                                    justify-center
                                "
                            >

                                <i
                                    data-lucide="chevron-down"
                                    class="
                                        w-4 h-4
                                        text-[#8a8a8a]

                                        transition-transform
                                        duration-300 ease-out
                                    "

                                    :class="{
                                        'rotate-180':
                                            expandedProductions.includes({{ $production->id }})
                                    }"
                                ></i>

                            </div>

                        </div>

                        <!-- EXPAND -->
                        <div
                            x-show="
                                expandedProductions.includes({{ $production->id }})
                            "
                            x-collapse.duration.250ms
                        >

                            <div
                                class="
                                    bg-[#f6faf7]
                                    border-b border-[#e8e8e5]

                                    px-6
                                    py-6
                                "
                            >

                                @php

                                    $consumption = [];

                                    foreach ($production->items as $item) {

                                        foreach ($item->menu->ingredients as $ingredient) {

                                            $materialId = $ingredient->id;

                                            $usage =
                                                $ingredient->pivot->quantity
                                                *
                                                $item->target_quantity;

                                            if (! isset($consumption[$materialId])) {

                                                $consumption[$materialId] = [

                                                    'name' =>
                                                        $ingredient->name,

                                                    'unit' =>
                                                        $ingredient->base_unit,

                                                    'quantity' => 0,
                                                ];
                                            }

                                            $consumption[$materialId]['quantity']
                                                += $usage;
                                        }
                                    }

                                @endphp

                                <div
                                    class="
                                        grid
                                        grid-cols-1
                                        xl:grid-cols-2

                                        gap-x-12
                                        gap-y-6
                                    "
                                >

                                    {{-- LEFT COLUMN --}}
                                    

                                    {{-- Production Items --}}
                                    <div>

                                        <div
                                            class="
                                                flex items-center gap-2
                                                mb-3
                                            "
                                        >

                                            <i
                                                data-lucide="chef-hat"
                                                class="
                                                    w-4 h-4
                                                    text-emerald-500
                                                "
                                            ></i>

                                            <h4
                                                class="
                                                    text-sm
                                                    font-semibold
                                                    text-[#2f2f2f]
                                                "
                                            >
                                                Production Items
                                            </h4>

                                        </div>

                                        <div
                                            class="
                                                border-t border-[#e8e8e5]
                                                pt-3
                                                space-y-2
                                            "
                                        >

                                            @foreach($production->items as $item)

                                                <div
                                                    class="
                                                        flex
                                                        justify-between
                                                        text-sm
                                                    "
                                                >

                                                    <span class="text-[#2f2f2f]">
                                                        {{ $item->menu->name }}
                                                    </span>

                                                    <span
                                                        class="
                                                            font-medium
                                                            text-[#2f2f2f]
                                                        "
                                                    >
                                                        {{ number_format($item->target_quantity) }}
                                                    </span>

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                    {{-- Waste Records --}}
                                    <div>

                                        <div
                                            class="
                                                flex items-center gap-2
                                                mb-3
                                            "
                                        >

                                            <i
                                                data-lucide="trash-2"
                                                class="
                                                    w-4 h-4
                                                    text-red-500
                                                "
                                            ></i>

                                            <h4
                                                class="
                                                    text-sm
                                                    font-semibold
                                                    text-[#2f2f2f]
                                                "
                                            >
                                                Waste Records
                                            </h4>

                                        </div>

                                        <div
                                            class="
                                                border-t border-[#e8e8e5]
                                                pt-3
                                                space-y-2
                                            "
                                        >

                                            @forelse($production->wastes as $waste)

                                                @php

                                                    $wasteQty =
                                                        fmod(
                                                            $waste->quantity,
                                                            1
                                                        ) == 0

                                                        ? number_format(
                                                            $waste->quantity,
                                                            0
                                                        )

                                                        : rtrim(
                                                            rtrim(
                                                                number_format(
                                                                    $waste->quantity,
                                                                    2
                                                                ),
                                                                '0'
                                                            ),
                                                            '.'
                                                        );

                                                @endphp

                                                <div
                                                    class="
                                                        flex
                                                        justify-between
                                                        text-sm
                                                    "
                                                >

                                                    <span class="text-[#2f2f2f]">
                                                        {{ $waste->rawMaterial->name }}
                                                    </span>

                                                    <span
                                                        class="
                                                            font-medium
                                                            text-red-600
                                                        "
                                                    >
                                                        {{ $wasteQty }}
                                                        {{ $waste->rawMaterial->base_unit }}
                                                    </span>

                                                </div>

                                            @empty

                                                <div
                                                    class="
                                                        text-sm
                                                        text-[#8a8a8a]
                                                    "
                                                >
                                                    No waste recorded.
                                                </div>

                                            @endforelse

                                        </div>

                                    </div>

                                    {{-- RIGHT COLUMN --}}

                                    {{-- Material Consumption --}}
                                    <div>

                                        <div
                                            class="
                                                flex items-center gap-2
                                                mb-3
                                            "
                                        >

                                            <i
                                                data-lucide="flask-conical"
                                                class="
                                                    w-4 h-4
                                                    text-blue-500
                                                "
                                            ></i>

                                            <h4
                                                class="
                                                    text-sm
                                                    font-semibold
                                                    text-[#2f2f2f]
                                                "
                                            >
                                                Material Consumption
                                            </h4>

                                        </div>

                                        <div
                                            class="
                                                border-t border-[#e8e8e5]
                                                pt-3
                                                space-y-2
                                            "
                                        >

                                            @forelse($consumption as $material)

                                                @php

                                                    $qty =
                                                        fmod(
                                                            $material['quantity'],
                                                            1
                                                        ) == 0

                                                        ? number_format(
                                                            $material['quantity'],
                                                            0
                                                        )

                                                        : rtrim(
                                                            rtrim(
                                                                number_format(
                                                                    $material['quantity'],
                                                                    2
                                                                ),
                                                                '0'
                                                            ),
                                                            '.'
                                                        );

                                                @endphp

                                                <div
                                                    class="
                                                        flex
                                                        justify-between
                                                        text-sm
                                                    "
                                                >

                                                    <span class="text-[#2f2f2f]">
                                                        {{ $material['name'] }}
                                                    </span>

                                                    <span
                                                        class="
                                                            font-medium
                                                            text-[#2f2f2f]
                                                        "
                                                    >
                                                        {{ $qty }}
                                                        {{ $material['unit'] }}
                                                    </span>

                                                </div>

                                            @empty

                                                <div
                                                    class="
                                                        text-sm
                                                        text-[#8a8a8a]
                                                    "
                                                >
                                                    No material usage.
                                                </div>

                                            @endforelse

                                        </div>

                                    </div>

                                    {{-- Production Information --}}
                                    <div>

                                        <div
                                            class="
                                                flex items-center gap-2
                                                mb-3
                                            "
                                        >

                                            <i
                                                data-lucide="clipboard-list"
                                                class="
                                                    w-4 h-4
                                                    text-amber-500
                                                "
                                            ></i>

                                            <h4
                                                class="
                                                    text-sm
                                                    font-semibold
                                                    text-[#2f2f2f]
                                                "
                                            >
                                                Production Information
                                            </h4>

                                        </div>

                                        <div
                                            class="
                                                border-t border-[#e8e8e5]
                                                pt-3
                                                space-y-2
                                            "
                                        >

                                            <div
                                                class="
                                                    flex
                                                    justify-between
                                                    text-sm
                                                "
                                            >

                                                <span class="text-[#8a8a8a]">
                                                    Created By
                                                </span>

                                                <span class="text-[#2f2f2f]">
                                                    {{ $production->creator?->name ?? '-' }}
                                                </span>

                                            </div>

                                            <div
                                                class="
                                                    flex
                                                    justify-between
                                                    text-sm
                                                "
                                            >

                                                <span class="text-[#8a8a8a]">
                                                    Status
                                                </span>

                                                <span class="text-[#2f2f2f]">
                                                    {{ ucfirst($production->status) }}
                                                </span>

                                            </div>

                                            <div
                                                class="
                                                    flex
                                                    justify-between
                                                    text-sm
                                                "
                                            >

                                                <span class="text-[#8a8a8a]">
                                                    Notes
                                                </span>

                                                <span class="text-[#2f2f2f]">
                                                    {{ $production->notes ?: '-' }}
                                                </span>

                                            </div>

                                            <div
                                                class="
                                                    flex
                                                    justify-between
                                                    text-sm
                                                "
                                            >

                                                <span class="text-[#8a8a8a]">
                                                    Execution Notes
                                                </span>

                                                <span class="text-[#2f2f2f]">
                                                    {{ $production->execution_notes ?: '-' }}
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

        <div
            class="
                px-6 py-4
                border-t border-[#e8e8e5]
            "
        >

            {{ $productions->links() }}

        </div>

    @endif

</div>