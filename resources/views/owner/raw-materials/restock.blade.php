@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div>

        <h1 class="text-3xl font-bold text-[#2c1f16]">
            Restock Material
        </h1>

        <p class="text-[#5c4432] mt-1">
            Tambahkan stock untuk material:
            <span class="font-medium">
                {{ $rawMaterial->name }}
            </span>
        </p>

    </div>

    <!-- FORM -->
    <form
        action="{{ route('owner.raw-materials.store-restock', $rawMaterial) }}"
        method="POST"
        class="space-y-6"
    >

        @csrf

        <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <!-- QUANTITY -->
                <div>

                    <label class="text-sm font-medium text-[#5c4432]">
                        Restock Quantity
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

                        Example: If you want to restock 5 {{ $rawMaterial->purchase_unit }},
                        enter "5" in the field.
                    </p>

                    @error('quantity')

                        <p class="text-xs text-red-500 mt-2">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

                <!-- PRICE -->
                <div>

                    <label class="text-sm font-medium text-[#5c4432]">
                        Latest Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="latest_price"
                        value="{{ old('latest_price') }}"
                        placeholder=""
                        class="w-full mt-2 rounded-2xl
                            border border-black/10
                            px-4 h-11"
                    >

                    @if($rawMaterial->latest_price)
 
                        <p class="text-xs text-[#5c4432] mt-1">

                            Current price:
                            Rp {{ number_format($rawMaterial->latest_price, 0, ',', '.') }}

                        </p>

                        <p class="text-xs text-[#5c4432] mt-2">

                            Leave empty if purchase price has not changed.

                        </p>

                    @else

                        <p class="text-xs text-red-500 mt-2">

                            First restock requires latest price.

                        </p>

                    @endif

                    @error('latest_price')

                        <p class="text-xs text-red-500 mt-2">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

            <!-- NOTES -->
            <div class="mt-6">

                <label class="text-sm font-medium text-[#5c4432]">
                    Notes
                </label>

                <textarea
                    name="notes"
                    rows="4"
                    placeholder="Optional notes..."
                    class="w-full mt-2 rounded-2xl
                        border border-black/10
                        px-4 py-3"
                >{{ old('notes') }}</textarea>

            </div>

        </div>

        <!-- ACTION -->
        <div class="flex items-center justify-end gap-3">

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

            <button
                type="submit"
                class="inline-flex items-center justify-center
                    h-11 px-6 rounded-2xl
                    bg-[#2c1f16] text-white font-medium
                    hover:opacity-90 transition"
            >
                Confirm Restock
            </button>

        </div>

    </form>

</div>

@endsection