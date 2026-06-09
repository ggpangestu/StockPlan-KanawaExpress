<!-- SUMMARY -->
<div
    class="
        grid
        grid-cols-1
        sm:grid-cols-2
        xl:grid-cols-4
        gap-4
    "
>

    <!-- TRANSACTIONS -->
    <div
        class="
            bg-white
            border border-[#e8e8e5]
            rounded-3xl
            p-4

            hover:shadow-md

            transition
        "
    >

        <div
            class="
                w-11 h-11
                rounded-2xl

                bg-emerald-50

                flex items-center justify-center
            "
        >

            <i
                data-lucide="activity"
                class="w-5 h-5 text-emerald-500"
            ></i>

        </div>

        <p
            class="
                mt-3
                text-sm
                text-[#8a8a8a]
            "
        >
            Transactions
        </p>

        <p
            class="
                mt-1
                text-3xl
                font-semibold
                text-[#2f2f2f]
            "
        >
            {{ number_format($totalTransactions) }}
        </p>

        <p
            class="
                mt-2
                text-xs
                text-[#8a8a8a]
            "
        >
            Total recorded activities
        </p>

    </div>

    <!-- RESTOCK -->
    <div
        class="
            bg-white
            border border-[#e8e8e5]
            rounded-3xl
            p-4

            hover:shadow-md

            transition
        "
    >

        <div
            class="
                w-11 h-11
                rounded-2xl

                bg-sky-50

                flex items-center justify-center
            "
        >

            <i
                data-lucide="package-plus"
                class="w-5 h-5 text-sky-500"
            ></i>

        </div>

        <p class="mt-3 text-sm text-[#8a8a8a]">
            Restocks
        </p>

        <p class="mt-1 text-3xl font-semibold text-[#2f2f2f]">
            {{ number_format($totalRestocks) }}
        </p>

        <p class="mt-2 text-xs text-[#8a8a8a]">
            Inventory replenishments
        </p>

    </div>

    <!-- ADJUSTMENTS -->
    <div
        class="
            bg-white
            border border-[#e8e8e5]
            rounded-3xl
            p-4

            hover:shadow-md

            transition
        "
    >

        <div
            class="
                w-11 h-11
                rounded-2xl

                bg-amber-50

                flex items-center justify-center
            "
        >

            <i
                data-lucide="scale"
                class="w-5 h-5 text-amber-500"
            ></i>

        </div>

        <p class="mt-3 text-sm text-[#8a8a8a]">
            Adjustments
        </p>

        <p class="mt-1 text-3xl font-semibold text-[#2f2f2f]">
            {{ number_format($totalAdjustments) }}
        </p>

        <p class="mt-2 text-xs text-[#8a8a8a]">
            Stock corrections
        </p>

    </div>

    <!-- PURCHASE VALUE -->
    <div
        class="
            bg-white
            border border-[#e8e8e5]
            rounded-3xl
            p-4

            hover:shadow-md

            transition
        "
    >

        <div
            class="
                w-11 h-11
                rounded-2xl

                bg-violet-50

                flex items-center justify-center
            "
        >

            <i
                data-lucide="banknote"
                class="w-5 h-5 text-violet-500"
            ></i>

        </div>

        <p class="mt-3 text-sm text-[#8a8a8a]">
            Purchase Value
        </p>

        <p class="mt-1 text-3xl font-semibold text-[#2f2f2f]">
            Rp {{ number_format($totalPurchaseValue, 0, ',', '.') }}
        </p>

        <p class="mt-2 text-xs text-[#8a8a8a]">
            Total purchase amount
        </p>

    </div>

</div>