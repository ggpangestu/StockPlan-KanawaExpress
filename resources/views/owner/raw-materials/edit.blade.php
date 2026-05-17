@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8">

    <!-- HEADER -->
    <div class="mb-8">

        <h1 class="text-3xl font-bold text-[#2c1f16]">
            Edit Raw Material
        </h1>

        <p class="text-[#5c4432] mt-2">
            Update material information and inventory configuration.
        </p>

    </div>

    <!-- FORM -->
    <form
        action="{{ route('owner.raw-materials.update', $rawMaterial) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-8"
    >

        @csrf
        @method('PUT')

        <!-- EDITABLE SECTION -->
        <div
            class="bg-white rounded-3xl
                border border-black/5
                p-6 space-y-6 shadow-sm"
        >

            <!-- SECTION HEADER -->
            <div>

                <h2 class="text-lg font-semibold text-[#2c1f16]">
                    Basic Information
                </h2>

                <p class="text-sm text-[#5c4432] mt-1">
                    Main information about the raw material.
                </p>

            </div>

            <!-- CONTENT -->
            <div
                class="grid grid-cols-1
                    xl:grid-cols-[160px_1fr]
                    gap-6"
            >

                <!-- IMAGE -->
                <div
                    x-data="{
                        preview: '{{ $rawMaterial->image
                            ? asset('storage/' . $rawMaterial->image)
                            : '' }}'
                    }"
                >

                    <label class="block text-sm font-medium text-[#5c4432] mb-2">
                        Material Image
                    </label>

                    <!-- PREVIEW -->
                    <div
                        class="w-36 h-36 rounded-3xl
                            border border-dashed border-black/10
                            bg-black/[0.02]
                            overflow-hidden"
                    >

                        <!-- IMAGE -->
                        <template x-if="preview">

                            <img
                                :src="preview"
                                class="w-full h-full object-cover"
                            >

                        </template>

                        <!-- PLACEHOLDER -->
                        <template x-if="!preview">

                            <div class="w-full h-full flex items-center justify-center">

                                <span class="text-xs text-[#5c4432] text-center px-3">
                                    Upload Image
                                </span>

                            </div>

                        </template>

                    </div>

                    <!-- INPUT -->
                    <input
                        type="file"
                        name="image"
                        accept="image/*"

                        @change="
                            const file = $event.target.files[0];

                            if (file) {
                                preview = URL.createObjectURL(file);
                            }
                        "

                        class="w-full mt-3 text-sm"
                    >

                    <p class="text-xs text-[#5c4432] mt-2">
                        JPG, PNG, WEBP • max 2MB
                    </p>

                    @error('image')

                        <p class="text-sm text-red-500 mt-2">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

                <!-- RIGHT -->
                <div class="space-y-5">

                    <!-- NAME -->
                    <div>

                        <label class="text-sm font-medium text-[#5c4432]">
                            Material Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $rawMaterial->name) }}"
                            class="w-full mt-2 rounded-2xl
                                border border-black/10
                                px-4 h-11"
                        >

                        @error('name')

                            <p class="text-sm text-red-500 mt-2">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    <!-- CATEGORY -->
                    <div>

                        <label class="text-sm font-medium text-[#5c4432]">
                            Category
                        </label>

                        <select
                            name="category"
                            class="w-full mt-2 rounded-2xl
                                border border-black/10
                                px-4 h-11"
                        >

                            <option value="">
                                Select Category
                            </option>

                            @foreach([
                                'Milk',
                                'Coffee Bean',
                                'Sweetener',
                                'Syrup',
                                'Powder',
                                'Packaging',
                                'Topping'
                            ] as $category)

                                <option
                                    value="{{ $category }}"
                                    @selected(
                                        old(
                                            'category',
                                            $rawMaterial->category
                                        ) === $category
                                    )
                                >
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                        @error('category')

                            <p class="text-sm text-red-500 mt-2">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>

            </div>

        </div>

        <!-- CONFIGURATION SECTION -->
        <div
            class="bg-white rounded-3xl
                border border-black/5
                p-6 space-y-6 mt-6 shadow-sm"
        >

            <div>

                <h2 class="text-lg font-semibold text-[#2c1f16]">
                    Inventory Configuration
                </h2>

                <p class="text-sm text-[#5c4432] mt-1">
                    Unit configuration and stock normalization.
                </p>

                @if($hasTransactions)

                    <div
                        class="mt-4 rounded-2xl
                            border border-yellow-200
                            bg-yellow-50
                            px-4 py-3"
                    >

                        <p class="text-sm text-yellow-800">

                            Inventory configuration is locked because
                            transactions already exist for this material.

                        </p>

                    </div>

                @endif

                <div
                    class="grid grid-cols-1
                        xl:grid-cols-[1fr_auto_1fr_auto_1fr]
                        gap-4 items-end"
                >

                    <!-- PURCHASE UNIT -->
                    <div>

                        <label class="text-sm font-medium text-[#5c4432]">
                            Purchase Unit
                        </label>

                        <select
                            name="purchase_unit"

                            @if($hasTransactions)
                                disabled
                            @endif

                            class="w-full mt-2 rounded-2xl
                                border border-black/10
                                px-4 h-11

                                @if($hasTransactions)
                                    bg-black/[0.03]
                                    text-[#5c4432]
                                @endif
                            "
                        >

                            @foreach([
                                'box',
                                'pack',
                                'bottle',
                                'bag'
                            ] as $unit)

                                <option
                                    value="{{ $unit }}"

                                    @selected(
                                        old(
                                            'purchase_unit',
                                            $rawMaterial->purchase_unit
                                        ) === $unit
                                    )
                                >
                                    {{ ucfirst($unit) }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- EQUAL -->
                    <div class="hidden xl:flex pb-3 text-[#5c4432] font-medium">

                        =

                    </div>

                    <!-- CONVERSION VALUE -->
                    <div>

                        <label class="text-sm font-medium text-[#5c4432]">
                            Quantity / Package
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="conversion_value"

                            value="{{ old(
                                'conversion_value',
                                rtrim(
                                    rtrim(
                                        number_format(
                                            $rawMaterial->conversion_value,
                                            2,
                                            '.',
                                            ''
                                        ),
                                        '0'
                                    ),
                                    '.'
                                )
                            ) }}"

                            @if($hasTransactions)
                                readonly
                            @endif

                            class="w-full mt-2 rounded-2xl
                                border border-black/10
                                px-4 h-11

                                @if($hasTransactions)
                                    bg-black/[0.03]
                                    text-[#5c4432]
                                @endif
                            "
                        >

                    </div>

                    <!-- MULTIPLY -->
                    <div class="hidden xl:flex pb-3 text-[#5c4432] font-medium">

                        ×

                    </div>

                    <!-- BASE UNIT -->
                    <div>

                        <label class="text-sm font-medium text-[#5c4432]">
                            Base Unit
                        </label>

                        <select
                            name="base_unit"

                            @if($hasTransactions)
                                disabled
                            @endif

                            class="w-full mt-2 rounded-2xl
                                border border-black/10
                                px-4 h-11

                                @if($hasTransactions)
                                    bg-black/[0.03]
                                    text-[#5c4432]
                                @endif
                            "
                        >

                            @foreach([
                                'ml',
                                'gram',
                                'pcs'
                            ] as $unit)

                                <option
                                    value="{{ $unit }}"

                                    @selected(
                                        old(
                                            'base_unit',
                                            $rawMaterial->base_unit
                                        ) === $unit
                                    )
                                >
                                    {{ $unit }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <p class="text-xs text-[#5c4432] mt-4">

                    Example:

                    @if($hasTransactions)

                        <p class="text-xs text-[#5c4432] mt-2">

                            Configuration fields are locked to preserve
                            transaction history integrity.

                        </p>

                    @endif

                    1 {{ $rawMaterial->purchase_unit }}
                    =
                    {{ number_format(
                        $rawMaterial->conversion_value,
                        0,
                        ',',
                        '.'
                    ) }}
                    {{ $rawMaterial->base_unit }}

                </p>

            </div>

        </div>

        <!-- MONITORING -->
        <div
            class="bg-white rounded-3xl
                border border-black/5
                p-6 space-y-6 mt-6 shadow-sm"
        >

            <div>

                <h2 class="text-lg font-semibold text-[#2c1f16]">
                    Monitoring
                </h2>

                <p class="text-sm text-[#5c4432] mt-1">
                    Used for stock monitoring and inventory alerts.
                </p>

            </div>

            <!-- MINIMUM STOCK -->
            <div>

                <label class="text-sm font-medium text-[#5c4432]">
                    Minimum Stock
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="minimum_stock"

                    value="{{ old(
                        'minimum_stock',
                        rtrim(
                            rtrim(
                                number_format(
                                    $rawMaterial->minimum_stock,
                                    2,
                                    '.',
                                    ''
                                ),
                                '0'
                            ),
                            '.'
                        )
                    ) }}"

                    class="w-full mt-2 rounded-2xl
                        border border-black/10
                        px-4 h-11"
                >

                <p class="text-xs text-[#5c4432] mt-2">
                    Minimum stock is measured using the base unit.
                </p>

                @error('minimum_stock')

                    <p class="text-sm text-red-500 mt-2">
                        {{ $message }}
                    </p>

                @enderror

            </div>

        </div>

        <!-- ACTION -->
        <div class="flex justify-end gap-3 mt-6">

            <a
                href="{{ route('owner.raw-materials.index') }}"
                class="inline-flex items-center justify-center
                    h-11 px-5 rounded-2xl
                    border border-black/10
                    bg-white text-[#2c1f16]
                    text-sm font-medium
                    hover:bg-black/[0.03]
                    transition"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center
                    h-11 px-5 rounded-2xl
                    bg-[#2c1f16] text-white
                    text-sm font-medium
                    hover:opacity-90
                    transition"
            >
                Update Material
            </button>

        </div>

    </form>

</div>

@endsection
