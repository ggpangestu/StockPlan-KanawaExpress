@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="mb-8">

        <a
            href="{{ route('owner.raw-materials.index') }}"
            class="inline-flex items-center gap-1
                text-sm text-[#8a8a8a]
                hover:text-[#2f2f2f]
                transition"
        >
            <i
                data-lucide="arrow-left"
                class="w-5 h-5"
            ></i>

            Back
        </a>

        <h1 class="text-3xl mt-3 font-bold text-[#2f2f2f]">
            Edit Raw Material
        </h1>

        <p class="text-[#8a8a8a] mt-2">
            Update material information and inventory configuration.
        </p>

    </div>

    <!-- FORM -->
    <form
        x-data="{ submitting: false }"

        @submit="submitting = true"

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
                border border-[#e8e8e5]
                p-6 space-y-6 shadow-sm"
        >

            <!-- SECTION HEADER -->
            <div>

                <h2 class="text-lg font-semibold text-[#2f2f2f]">
                    Basic Information
                </h2>

                <p class="text-sm text-[#8a8a8a] mt-1">
                    Basic information about the raw material.
                </p>

            </div>

            <!-- CONTENT -->
            <div
                x-data="{
                    preview: '{{ $rawMaterial->image
                        ? asset('storage/' . $rawMaterial->image)
                        : '' }}',

                    imageError: '',
                    fileName: '',
                }"

                class="grid grid-cols-1
                    md:grid-cols-[240px_1fr]
                    gap-6"
            >

                <!-- IMAGE -->
                <div>

                    <label class="block text-sm font-medium text-[#2f2f2f] mb-2">
                        Material Image
                    </label>

                    <!-- PREVIEW -->
                    <div
                        class="w-full h-[240px]
                            rounded-3xl
                            border border-dashed border-[#e8e8e5]
                            bg-black/[0.02]
                            overflow-hidden"
                    >

                        <template x-if="preview">

                            <img
                                :src="preview"
                                alt="Preview"
                                class="w-full h-full object-cover"
                            >

                        </template>

                        <template x-if="!preview">

                            <div
                                class="w-full h-full
                                    flex flex-col
                                    items-center justify-center"
                            >

                                <span class="text-sm text-[#2f2f2f]">
                                    No Image
                                </span>

                                <span class="text-xs text-[#8a8a8a] mt-1">
                                    Preview will appear here
                                </span>

                            </div>

                        </template>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="space-y-5">

                    <!-- NAME -->
                    <div>

                        <label class="text-sm font-medium text-[#2f2f2f]">
                            Material Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $rawMaterial->name) }}"

                            @class([

                                'w-full mt-2 rounded-2xl
                                transition
                                px-4 h-11
                                focus:outline-none
                                focus:ring-2
                                focus:ring-black/5
                                focus:border-[#2f2f2f]/10',

                                'border border-rose-300' =>
                                    $errors->has('name'),

                                'border border-[#e8e8e5]' =>
                                    !$errors->has('name'),

                            ])
                        >

                        @error('name')

                            <p class="text-xs text-rose-600 mt-2">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    <!-- CATEGORY -->
                    <div>

                        <label class="text-sm font-medium text-[#2f2f2f]">
                            Category
                        </label>

                        <select
                            name="category"

                            @class([

                                'w-full mt-2 rounded-2xl
                                transition
                                px-4 h-11
                                focus:outline-none
                                focus:ring-2
                                focus:ring-black/5
                                focus:border-[#2f2f2f]/10',

                                'border border-rose-300' =>
                                    $errors->has('category'),

                                'border border-[#e8e8e5]' =>
                                    !$errors->has('category'),

                            ])
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

                            <p class="text-xs text-rose-600 mt-2">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    <!-- IMAGE UPLOAD -->
                    <div>

                        <label class="text-sm font-medium text-[#2f2f2f]">
                            Material Image
                        </label>

                        <input
                            type="file"
                            name="image"
                            accept="image/*"

                            @change="
                                const file = $event.target.files[0];

                                imageError = '';

                                if (!file) return;

                                const allowedTypes = [
                                    'image/jpeg',
                                    'image/png',
                                    'image/webp'
                                ];

                                if (!allowedTypes.includes(file.type)) {

                                    imageError =
                                        'Only JPG, PNG, and WEBP images are allowed.';

                                    $event.target.value = '';

                                    return;
                                }

                                if (file.size > 2097152) {

                                    imageError =
                                        'Image size must not exceed 2 MB.';

                                    $event.target.value = '';

                                    return;
                                }

                                if (preview?.startsWith('blob:')) {
                                    URL.revokeObjectURL(preview);
                                }

                                preview = URL.createObjectURL(file);
                            "

                            class="
                                block w-full mt-2

                                focus:outline-none
                                focus:ring-0
                                focus-visible:outline-none

                                text-sm text-[#8a8a8a]

                                file:h-11
                                file:px-5
                                file:mr-4

                                file:rounded-2xl

                                file:border
                                file:border-[#e8e8e5]

                                file:bg-white
                                file:text-[#2f2f2f]

                                file:text-sm
                                file:font-medium

                                file:hover:bg-[#f3f3f1]

                                file:cursor-pointer

                                cursor-pointer
                            "
                        >

                        <template x-if="imageError">

                            <p
                                x-text="imageError"
                                class="text-xs text-rose-600 mt-2"
                            ></p>

                        </template>

                        <template x-if="!imageError">

                            @if($errors->has('image'))

                                <p class="text-xs text-rose-600 mt-2">
                                    {{ $errors->first('image') }}
                                </p>

                            @else

                                <p class="text-xs text-[#8a8a8a] mt-2">
                                    JPG, JPEG, PNG, WEBP • Maximum 2 MB
                                </p>

                            @endif

                        </template>

                    </div>

                </div>

            </div>

        </div>

        <!-- CONFIGURATION SECTION -->
        <div
            class="bg-white rounded-3xl
                border border-[#e8e8e5]
                p-6 space-y-6 mt-6 shadow-sm"
        >

            <div>

                <h2 class="text-lg font-semibold text-[#2f2f2f]">
                    Inventory Configuration
                </h2>

                <p class="text-sm text-[#8a8a8a] mt-1">
                    Unit configuration and stock normalization.
                </p>

                @if($hasTransactions)

                    <div
                        class="mt-4 rounded-2xl
                            border border-amber-200
                            bg-amber-50
                            px-4 py-3"
                    >

                        <p class="text-sm text-amber-800">
                            Inventory configuration is locked because transactions already exist for this material, preventing changes that could affect transaction history integrity.
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

                        <label class="text-sm font-medium text-[#2f2f2f]">
                            Purchase Unit
                        </label>

                        <select
                            name="purchase_unit"

                            @if($hasTransactions)
                                disabled
                            @endif

                            class="w-full mt-2 rounded-2xl
                                border border-[#e8e8e5]
                                transition
                                px-4 h-11
                                focus:outline-none
                                focus:ring-2
                                focus:ring-black/5
                                focus:border-[#2f2f2f]/10
                                {{ $hasTransactions
                                    ? 'bg-black/[0.03] text-[#2f2f2f] cursor-not-allowed select-none'
                                    : ''
                                }}"
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

                        <p class="text-xs text-[#8a8a8a] mt-2">
                            Unit used when purchasing this material.
                        </p>

                    </div>

                    <!-- EQUAL -->
                    <div class="hidden xl:flex pb-8 text-[#2f2f2f] font-medium">

                        =

                    </div>

                    <!-- CONVERSION VALUE -->
                    <div>

                        <label class="text-sm font-medium text-[#2f2f2f]">
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

                            @class([

                                'w-full mt-2 rounded-2xl
                                transition
                                px-4 h-11
                                focus:outline-none
                                focus:ring-2
                                focus:ring-black/5
                                focus:border-[#2f2f2f]/10',

                                'border border-rose-300' =>
                                    $errors->has('conversion_value'),

                                'border border-[#e8e8e5]' =>
                                    !$errors->has('conversion_value'),

                                'bg-black/[0.03] text-[#2f2f2f] cursor-not-allowed select-none' =>
                                    $hasTransactions,

                            ])
                        >

                        @if($errors->has('conversion_value'))

                            <p class="text-xs text-rose-600 mt-2">
                                {{ $errors->first('conversion_value') }}
                            </p>

                        @else

                            <p class="text-xs text-[#8a8a8a] mt-2">
                                Amount contained in one purchase unit.
                            </p>

                        @endif

                    </div>

                    <!-- MULTIPLY -->
                    <div class="hidden xl:flex pb-8 text-[#2f2f2f] font-medium">

                        ×

                    </div>

                    <!-- BASE UNIT -->
                    <div>

                        <label class="text-sm font-medium text-[#2f2f2f]">
                            Base Unit
                        </label>

                        <select
                            name="base_unit"

                            @if($hasTransactions)
                                disabled
                            @endif

                            class="w-full mt-2 rounded-2xl
                                border border-[#e8e8e5]
                                transition
                                px-4 h-11
                                focus:outline-none
                                focus:ring-2
                                focus:ring-black/5
                                focus:border-[#2f2f2f]/10
                                {{ $hasTransactions
                                    ? 'bg-black/[0.03] text-[#2f2f2f] cursor-not-allowed select-none'
                                    : ''
                                }}"
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

                        <p class="text-xs text-[#8a8a8a] mt-2">
                            Smallest unit used for stock tracking.
                        </p>

                    </div>

                </div>

            </div>

            <div class="text-xs text-[#8a8a8a] mt-4">
                Example:
                1 {{ $rawMaterial->purchase_unit }}
                =
                {{ number_format(
                    $rawMaterial->conversion_value,
                    0,
                    ',',
                    '.'
                ) }}
                {{ $rawMaterial->base_unit }}
            </div>

        </div>

        <!-- MONITORING -->
        <div
            class="bg-white rounded-3xl
                border border-[#e8e8e5]
                p-6 space-y-6 mt-6 shadow-sm"
        >

            <div>

                <h2 class="text-lg font-semibold text-[#2f2f2f]">
                    Monitoring
                </h2>

                <p class="text-sm text-[#2f2f2f] mt-1">
                    Set the minimum stock level before this material needs attention.
                </p>

            </div>

            <!-- MINIMUM STOCK -->
            <div>

                <label class="text-sm font-medium text-[#2f2f2f]">
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
                        border border-[#e8e8e5]
                        transition
                        px-4 h-11
                        focus:outline-none
                        focus:ring-2
                        focus:ring-black/5
                        focus:border-[#2f2f2f]/10"
                >

                @if($errors->has('minimum_stock'))

                    <p class="text-xs text-rose-600 mt-2">
                        {{ $errors->first('minimum_stock') }}
                    </p>

                @else

                    <p class="text-xs text-[#8a8a8a] mt-2">
                        Minimum stock is measured using the base unit.
                    </p>

                @endif

            </div>

        </div>

        <!-- ACTION -->
        <div class="flex justify-end gap-3 mt-6">

            <a
                href="{{ route('owner.raw-materials.index') }}"
                class="inline-flex items-center justify-center
                    h-11 px-5 rounded-2xl
                    border border-[#e8e8e5]
                    bg-white text-[#2f2f2f]
                    hover:bg-[#f3f3f1]
                    text-sm font-medium
                    transition duration-200"
            >
                Cancel
            </a>

            <button
                type="submit"

                :disabled="submitting"

                :class="{
                    'opacity-50 cursor-not-allowed hover:opacity-50': submitting
                }"

                class="inline-flex items-center justify-center
                    h-11 px-5 rounded-2xl
                    bg-[#2f2f2f] text-white
                    text-sm font-medium
                    hover:opacity-90
                    transition duration-200"
            >
                <span
                    x-cloak
                    x-show="!submitting"
                    x-transition.opacity
                >
                    Update Material
                </span>

                <span
                    x-cloak
                    x-show="submitting"
                    x-transition.opacity
                >
                    Updating...
                </span>
            </button>

        </div>

    </form>

</div>

@endsection
