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

    <!-- COMPLETED PRODUCTIONS -->
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
                data-lucide="book-check"
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
            Completed Productions
        </p>

        <p
            class="
                mt-1
                text-3xl
                font-semibold
                text-[#2f2f2f]
            "
        >
            {{ number_format($totalProductions) }}
        </p>

        <p
            class="
                mt-2
                text-xs
                text-[#8a8a8a]
            "
        >
            Completed production batches
        </p>

    </div>

    <!-- PRODUCED PORTIONS -->
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
                data-lucide="cup-soda"
                class="w-5 h-5 text-sky-500"
            ></i>

        </div>

        <p class="mt-3 text-sm text-[#8a8a8a]">
            Produced Portions
        </p>

        <p class="mt-1 text-3xl font-semibold text-[#2f2f2f]">
            {{ number_format($totalPortions) }}
        </p>

        <p class="mt-2 text-xs text-[#8a8a8a]">
            Total planned production output
        </p>

    </div>

    <!-- WASTE RECORDS -->
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

                bg-red-50

                flex items-center justify-center
            "
        >

            <i
                data-lucide="trash-2"
                class="w-5 h-5 text-red-500"
            ></i>

        </div>

        <p class="mt-3 text-sm text-[#8a8a8a]">
            Waste Incidents
        </p>

        <p class="mt-1 text-3xl font-semibold text-[#2f2f2f]">
            {{ number_format($totalWasteRecords) }}
        </p>

        <p class="mt-2 text-xs text-[#8a8a8a]">
            Recorded waste entries
        </p>

    </div>

    <!-- AVERAGE DAILY PRODUCTION -->
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
                data-lucide="activity"
                class="w-5 h-5 text-amber-500"
            ></i>

        </div>

        <p class="mt-3 text-sm text-[#8a8a8a]">
            Average Daily Production
        </p>

        <p class="mt-1 text-3xl font-semibold text-[#2f2f2f]">
            {{ number_format($averageDailyProduction) }}
        </p>

        <p class="mt-2 text-xs text-[#8a8a8a]">
            Portions produced per day
        </p>

    </div>

</div>