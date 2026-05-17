@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">


        <h1 class="text-3xl font-bold text-[#2c1f16]">
            Create Raw Material
        </h1>

        <p class="text-[#5c4432] mt-1">
            Tambahkan master bahan baku baru untuk Kanawa Express.
        </p>

    </div>

    <!-- FORM -->
    <form
        action="{{ route('owner.raw-materials.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
    >

        @csrf

        <!-- BASIC INFORMATION -->
        <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">

            <div class="mb-6">

                <h2 class="text-lg font-semibold text-[#2c1f16]">
                    Basic Information
                </h2>

                <p class="text-sm text-[#5c4432] mt-1">
                    Informasi utama bahan baku.
                </p>

            </div>

            <div class="grid grid-cols-1 xl:grid-cols-[160px_1fr] gap-6">

                <!-- IMAGE -->
                <div
                    x-data="{
                        preview: null
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

                        <!-- IMAGE PREVIEW -->
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
                            value="{{ old('name') }}"
                            placeholder="Example: Diamond UHT 1L"
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

                            <option value="Milk">
                                Milk
                            </option>

                            <option value="Coffee Bean">
                                Coffee Bean
                            </option>

                            <option value="Sweetener">
                                Sweetener
                            </option>

                            <option value="Syrup">
                                Syrup
                            </option>

                            <option value="Powder">
                                Powder
                            </option>

                            <option value="Packaging">
                                Packaging
                            </option>

                            <option value="Topping">
                                Topping
                            </option>

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

        <!-- UNIT CONFIGURATION -->
        <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">

            <div class="mb-6">

                <h2 class="text-lg font-semibold text-[#2c1f16]">
                    Unit Configuration
                </h2>

                <p class="text-sm text-[#5c4432] mt-1">
                    Konfigurasi unit pembelian dan penggunaan bahan baku.
                </p>

            </div>

            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto_1fr_auto_1fr] gap-4 items-end">

                <!-- PURCHASE UNIT -->
                <div>

                    <label class="text-sm font-medium text-[#5c4432]">
                        Purchase Unit
                    </label>

                    <select
                        name="purchase_unit"
                        class="w-full mt-2 rounded-2xl
                            border border-black/10
                            px-4 h-11"
                    >

                        <option value="box">
                            Box
                        </option>

                        <option value="pack">
                            Pack
                        </option>

                        <option value="bottle">
                            Bottle
                        </option>

                        <option value="bag">
                            Bag
                        </option>

                    </select>

                </div>

                <!-- EQUAL -->
                <div class="hidden xl:flex pb-3 text-[#5c4432] font-medium">

                    =

                </div>

                <!-- PACKAGE SIZE -->
                <div>

                    <label class="text-sm font-medium text-[#5c4432]">
                        Quantity / Package
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="conversion_value"
                        value="{{ old('conversion_value') }}"
                        placeholder="1000"
                        class="w-full mt-2 rounded-2xl
                            border border-black/10
                            px-4 h-11"
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
                        class="w-full mt-2 rounded-2xl
                            border border-black/10
                            px-4 h-11"
                    >

                        <option value="ml">
                            ml
                        </option>

                        <option value="gram">
                            gram
                        </option>

                        <option value="pcs">
                            pcs
                        </option>

                    </select>

                </div>

            </div>

            <p class="text-xs text-[#5c4432] mt-4">

                Example:
                1 pack = 1000 ml

            </p>

        </div>

        <!-- MONITORING -->
        <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">

            <div class="mb-6">

                <h2 class="text-lg font-semibold text-[#2c1f16]">
                    Monitoring
                </h2>

                <p class="text-sm text-[#5c4432] mt-1">
                    Digunakan untuk monitoring stok dan costing.
                </p>

            </div>

            <div>
                <!-- MINIMUM STOCK -->

                <label class="text-sm font-medium text-[#5c4432]">
                    Minimum Stock
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="minimum_stock"
                    value="{{ old('minimum_stock') }}"
                    class="w-full mt-2 rounded-2xl
                        border border-black/10
                        px-4 h-11"
                >

                <p class="text-xs text-[#5c4432] mt-2">
                    Mengikuti base unit yang dipilih.
                </p>
            </div>

        </div>

        <!-- ACTION -->
        <div class="flex items-center justify-end gap-3">

            <!-- CANCEL -->
            <a
                href="{{ route('owner.raw-materials.index') }}"
                class="inline-flex items-center justify-center
                    h-11 px-5 rounded-2xl
                    border border-black/10
                    text-[#2c1f16] font-medium
                    hover:bg-black/[0.03]
                    transition"
            >
                Cancel
            </a>

            <!-- SUBMIT -->
            <button
                type="submit"
                class="inline-flex items-center justify-center
                    h-11 px-6 rounded-2xl
                    bg-[#2c1f16] text-white font-medium
                    hover:opacity-90 transition"
            >
                Save Material
            </button>

        </div>

    </form>

</div>

@endsection