<div
    class="
        grid
        grid-cols-1
        md:grid-cols-3
        gap-4
        mt-4
    "
>

    <div
        class="
            bg-white
            border border-[#e8e8e5]
            rounded-3xl
            p-5
        "
    >

        <div class="flex items-center gap-2">

            <i
                data-lucide="coffee"
                class="
                    w-5 h-5
                    text-amber-500
                "
            ></i>

            <span
                class="
                    text-sm
                    font-medium
                    text-[#8a8a8a]
                "
            >
                Top Produced Menu
            </span>

        </div>

        <p
            class="
                mt-4
                text-xl
                font-semibold
                text-[#2f2f2f]
            "
        >
            {{ $topMenu['name'] ?? '-' }}
        </p>

        <p
            class="
                text-sm
                text-[#8a8a8a]
            "
        >
            {{ number_format($topMenu['total'] ?? 0) }}
            portions
        </p>

    </div>

    <div
        class="
            bg-white
            border border-[#e8e8e5]
            rounded-3xl
            p-5
        "
    >

        <div class="flex items-center gap-2">

            <i
                data-lucide="flask-conical"
                class="
                    w-5 h-5
                    text-blue-500
                "
            ></i>

            <span
                class="
                    text-sm
                    font-medium
                    text-[#8a8a8a]
                "
            >
                Most Consumed Material
            </span>

        </div>

        <p
            class="
                mt-4
                text-xl
                font-semibold
                text-[#2f2f2f]
            "
        >
            {{ $topMaterial['name'] ?? '-' }}
        </p>

        <p
            class="
                text-sm
                text-[#8a8a8a]
            "
        >
            {{ $topMaterial['quantity'] ?? 0 }}
            {{ $topMaterial['unit'] ?? '' }}
        </p>

    </div>

    <div
        class="
            bg-white
            border border-[#e8e8e5]
            rounded-3xl
            p-5
        "
    >

        <div class="flex items-center gap-2">

            <i
                data-lucide="trash-2"
                class="
                    w-5 h-5
                    text-red-500
                "
            ></i>

            <span
                class="
                    text-sm
                    font-medium
                    text-[#8a8a8a]
                "
            >
                Highest Waste Material
            </span>

        </div>

        <p
            class="
                mt-4
                text-xl
                font-semibold
                text-[#2f2f2f]
            "
        >
            {{ $topWasteMaterial['name'] ?? '-' }}
        </p>

        <p
            class="
                text-sm
                text-[#8a8a8a]
            "
        >
            {{ $topWasteMaterial['quantity'] ?? 0 }}
            {{ $topWasteMaterial['unit'] ?? '' }}
        </p>

    </div>

</div>