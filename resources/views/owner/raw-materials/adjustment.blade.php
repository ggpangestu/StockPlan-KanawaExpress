@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-[#2c1f16]">
                Stock Adjustment
            </h1>

            <p class="text-[#5c4432] mt-1">
                Correct active stock for this material.
            </p>

        </div>

        <a
            href="{{ route('owner.raw-materials.index') }}"
            class="inline-flex items-center justify-center
                h-11 px-5 rounded-2xl
                border border-black/10
                text-[#2c1f16]
                hover:bg-black/[0.03]
                transition"
        >
            Back
        </a>

    </div>

    <!-- MATERIAL INFO -->
    <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">

        <div class="flex items-center gap-4">

            <!-- IMAGE -->
            @if($rawMaterial->image)

                <img
                    src="{{ asset('storage/' . $rawMaterial->image) }}"
                    alt="{{ $rawMaterial->name }}"
                    class="w-16 h-16 rounded-2xl object-cover border border-black/5"
                >

            @else

                <div
                    class="w-16 h-16 rounded-2xl
                        bg-black/[0.04]
                        border border-black/5"
                ></div>

            @endif

            <!-- INFO -->
            <div>

                <h2 class="text-lg font-semibold text-[#2c1f16]">

                    {{ $rawMaterial->name }}

                </h2>

                <p class="text-sm text-[#5c4432] mt-1">

                    Current opened stock:

                    <span class="font-medium text-[#2c1f16]">

                        {{ number_format($rawMaterial->opened_stock, 0, ',', '.') }}

                        {{ $rawMaterial->base_unit }}

                    </span>

                </p>

            </div>

        </div>

    </div>

    <!-- FORM -->
    <form
        action="{{ route('owner.raw-materials.store-adjustment', $rawMaterial) }}"
        method="POST"
        class="bg-white rounded-3xl border border-black/5 shadow-sm p-6 space-y-6"
    >

        @csrf

        <!-- TYPE -->
        <div>

            <label class="text-sm font-medium text-[#5c4432]">
                Adjustment Type
            </label>

            <select
                name="type"
                class="w-full mt-2 rounded-2xl
                    border border-black/10
                    px-4 h-11"
            >

                <option value="adjustment_add">
                    Add Stock
                </option>

                <option value="adjustment_reduce">
                    Reduce Stock
                </option>

            </select>

        </div>

        <!-- QUANTITY -->
        <div>

            <label class="text-sm font-medium text-[#5c4432]">
                Quantity
            </label>

            <input
                type="number"
                step="0.01"
                name="quantity"
                value="{{ old('quantity') }}"
                class="w-full mt-2 rounded-2xl
                    border border-black/10
                    px-4 h-11"
            >

            <p class="text-xs text-[#5c4432] mt-2">

                Adjustment uses base unit:

                {{ $rawMaterial->base_unit }}

            </p>

        </div>

        <!-- NOTES -->
        <div>

            <label class="text-sm font-medium text-[#5c4432]">
                Notes
            </label>

            <textarea
                name="notes"
                rows="4"
                class="w-full mt-2 rounded-2xl
                    border border-black/10
                    px-4 py-3"
            >{{ old('notes') }}</textarea>

            <p class="text-xs text-[#5c4432] mt-2">
                Optional reason or explanation for this adjustment.
            </p>

        </div>

        <!-- ACTION -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-black/5">

            <a
                href="{{ route('owner.raw-materials.index') }}"
                class="inline-flex items-center justify-center
                    h-11 px-5 rounded-2xl
                    border border-black/10
                    text-[#2c1f16]
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
                    font-medium hover:opacity-90 transition"
            >
                Confirm Adjustment
            </button>

        </div>

    </form>

</div>

@endsection