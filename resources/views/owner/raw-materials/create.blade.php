@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div>

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
            Create Raw Material
        </h1>

        <p class="text-[#8a8a8a] mt-1">
            Create a new raw material for Kanawa Express.
        </p>

    </div>

    <!-- FORM -->
    <form

        x-data="{ submitting: false }"

        @submit="submitting = true"

        action="{{ route('owner.raw-materials.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
    >

        @csrf

        <!-- BASIC INFORMATION -->
        <div class="bg-white rounded-3xl border border-[#e8e8e5] shadow-sm p-6">

            <div class="mb-6">

                <h2 class="text-lg font-semibold text-[#2f2f2f]">
                    Basic Information
                </h2>

                <p class="text-sm text-[#8a8a8a] mt-1">
                    Main information about the raw material.
                </p>

            </div>

            <div
                
                x-data="{
                    preview: null,
                    fileName: '',
                    imageError: '',

                    cleanup() {
                        if (this.preview?.startsWith('blob:')) {
                            URL.revokeObjectURL(this.preview);
                        }
                    }
                }"

                x-on:destroy.window="cleanup()"

                class="grid grid-cols-1 md:grid-cols-[240px_1fr] gap-6"
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
                            value="{{ old('name') }}"
                            placeholder="Enter material name"
                            @class([

                                'w-full mt-2 rounded-2xl px-4 h-11 transition
                                focus:outline-none
                                focus:ring-2
                                focus:ring-black/5
                                focus:border-[#d8d8d5]',

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

                                'w-full mt-2 rounded-2xl px-4 h-11 transition
                                focus:outline-none
                                focus:ring-2
                                focus:ring-black/5
                                focus:border-[#d8d8d5]',

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
                                    @selected(old('category') === $category)
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

                        <div class="mt-2">

                            <input
                                type="file"
                                name="image"
                                accept="image/*"

                                @change="
                                    const file = $event.target.files[0];

                                    imageError = '';

                                    if (!file) return;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | FILE TYPE
                                    |--------------------------------------------------------------------------
                                    */

                                    const allowedTypes = [
                                        'image/jpeg',
                                        'image/jpg',
                                        'image/png',
                                        'image/webp'
                                    ];

                                    if (!allowedTypes.includes(file.type)) {

                                        imageError =
                                            'Only JPG, PNG, and WEBP images are allowed.';

                                        $event.target.value = '';

                                        fileName = '';

                                        if (preview) {
                                            URL.revokeObjectURL(preview);
                                        }

                                        preview = null;

                                        return;
                                    }

                                    /*
                                    |--------------------------------------------------------------------------
                                    | FILE SIZE
                                    |--------------------------------------------------------------------------
                                    */

                                    if (file.size > 2097152) {

                                        imageError =
                                            'Image size must not exceed 2 MB.';

                                        $event.target.value = '';

                                        fileName = '';

                                        if (preview) {
                                            URL.revokeObjectURL(preview);
                                        }

                                        preview = null;

                                        return;
                                    }

                                    fileName = file.name;

                                    if (preview) {
                                        URL.revokeObjectURL(preview);
                                    }

                                    preview = URL.createObjectURL(file);
                                "

                                class="
                                    block w-full
                                    focus:outline-none
                                    focus:ring-0
                                    focus:border-transparent

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

                        </div>

                        <template x-if="imageError">

                            <p
                                x-text="imageError"
                                class="text-xs text-rose-600 mt-2"
                            ></p>

                        </template>

                        <template x-if="!imageError">

                            <p class="text-xs text-[#8a8a8a] mt-2">
                                JPG, PNG, WEBP • Maximum 2 MB
                            </p>

                        </template>

                    </div>

                </div>

            </div>

        </div>

        <!-- UNIT CONFIGURATION -->
        <div class="bg-white rounded-3xl border border-[#e8e8e5] shadow-sm p-6">

            <div class="mb-6">

                <h2 class="text-lg font-semibold text-[#2f2f2f]">
                    Unit Configuration
                </h2>

                <p class="text-sm text-[#8a8a8a] mt-1">
                    Configure purchase and usage units for this material.
                </p>

            </div>

            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto_1fr_auto_1fr] gap-4 items-end">

                <!-- PURCHASE UNIT -->
                <div>

                    <label class="text-sm font-medium text-[#2f2f2f]">
                        Purchase Unit
                    </label>

                    <select
                        name="purchase_unit"
                        class="w-full mt-2 rounded-2xl
                            border border-[#e8e8e5]
                            px-4 h-11
                            transition
                            focus:outline-none
                            focus:ring-2
                            focus:ring-black/5
                            focus:border-[#d8d8d5]"
                    >

                        <option
                            value="box"
                            @selected(old('purchase_unit', 'box') === 'box')
                        >
                            Box
                        </option>

                        <option
                            value="pack"
                            @selected(old('purchase_unit') === 'pack')
                        >
                            Pack
                        </option>

                        <option
                            value="bottle"
                            @selected(old('purchase_unit') === 'bottle')
                        >
                            Bottle
                        </option>

                        <option
                            value="bag"
                            @selected(old('purchase_unit') === 'bag')
                        >
                            Bag
                        </option>

                    </select>

                    <p class="text-xs text-[#8a8a8a] mt-2">
                        Unit used when purchasing this material.
                    </p>

                </div>

                <!-- EQUAL -->
                <div class="hidden xl:flex pb-8 text-[#2f2f2f] font-medium">

                    =

                </div>

                <!-- PACKAGE SIZE -->
                <div>

                    <label class="text-sm font-medium text-[#2f2f2f]">
                        Quantity / Package
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="conversion_value"
                        value="{{ old('conversion_value') }}"
                        placeholder="1000"
                        @class([

                            'w-full mt-2 rounded-2xl px-4 h-11 transition
                            focus:outline-none
                            focus:ring-2
                            focus:ring-black/5
                            focus:border-[#d8d8d5]',

                            'border border-rose-300' =>
                                $errors->has('conversion_value'),

                            'border border-[#e8e8e5]' =>
                                !$errors->has('conversion_value'),

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
                        class="w-full mt-2 rounded-2xl
                            border border-[#e8e8e5]
                            px-4 h-11
                            transition
                            focus:outline-none
                            focus:ring-2
                            focus:ring-black/5
                            focus:border-[#d8d8d5]"
                    >

                        <option
                            value="ml"
                            @selected(old('base_unit', 'ml') === 'ml')
                        >
                            ml
                        </option>

                        <option
                            value="gram"
                            @selected(old('base_unit') === 'gram')
                        >
                            gram
                        </option>

                        <option
                            value="pcs"
                            @selected(old('base_unit') === 'pcs')
                        >
                            pcs
                        </option>

                    </select>

                    <p class="text-xs text-[#8a8a8a] mt-2">
                        Smallest unit used for stock tracking.
                    </p>

                </div>

            </div>

            <p class="text-xs text-[#8a8a8a] mt-4">
                For example, 1 pack = 1000 ml.
            </p>

        </div>

        <!-- MONITORING -->
        <div class="bg-white rounded-3xl border border-[#e8e8e5] shadow-sm p-6">

            <div class="mb-6">

                <h2 class="text-lg font-semibold text-[#2f2f2f]">
                    Monitoring
                </h2>

                <p class="text-sm text-[#8a8a8a] mt-1">
                    Set the minimum stock level before this material needs attention.
                </p>

            </div>

            <div>
                <!-- MINIMUM STOCK -->

                <label class="text-sm font-medium text-[#2f2f2f]">
                    Minimum Stock
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="minimum_stock"
                    value="{{ old('minimum_stock') }}"
                    @class([

                        'w-full mt-2 rounded-2xl px-4 h-11 transition
                        focus:outline-none
                        focus:ring-2
                        focus:ring-black/5
                        focus:border-[#d8d8d5]',

                        'border border-rose-300' =>
                            $errors->has('minimum_stock'),

                        'border border-[#e8e8e5]' =>
                            !$errors->has('minimum_stock'),

                    ])
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
        <div
            class="flex flex-col sm:flex-row
                items-stretch sm:items-center
                justify-end gap-3"
        >

            <!-- CANCEL -->
            <a
                href="{{ route('owner.raw-materials.index') }}"
                class="inline-flex items-center justify-center
                    h-11 px-5 rounded-2xl
                    border border-[#e8e8e5]
                    bg-white
                    text-[#2f2f2f] font-medium
                    hover:bg-[#f3f3f1]
                    transition duration-200"
            >
                Cancel
            </a>

            <!-- SUBMIT -->
            <button
                type="submit"

                :disabled="submitting"

                :class="{
                    'opacity-50 cursor-not-allowed hover:opacity-50': submitting
                }"
                class="inline-flex items-center justify-center
                    h-11 px-6 rounded-2xl
                    bg-[#2f2f2f] text-white font-medium
                    hover:opacity-90 transition duration-200"
            >
                <span
                    x-cloak
                    x-show="!submitting"
                    x-transition.opacity
                >
                    Save Material
                </span>

                <span
                    x-cloak
                    x-show="submitting"
                    x-transition.opacity
                >
                    Saving...
                </span>
            </button>

        </div>

    </form>

</div>

@endsection