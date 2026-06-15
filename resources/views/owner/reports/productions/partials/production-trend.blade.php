<div
    class="
        bg-white
        border border-[#e8e8e5]
        rounded-3xl
        p-6
    "
>

    <div
        class="
            flex items-center gap-3
        "
    >

        <div
            class="
                w-10 h-10
                rounded-2xl
                bg-emerald-50

                flex
                items-center
                justify-center
            "
        >

            <i
                data-lucide="chart-line"
                class="
                    w-5 h-5
                    text-emerald-500
                "
            ></i>

        </div>

        <div>

            <h2
                class="
                    text-lg
                    font-semibold
                "
            >
                Production Trend
            </h2>

            <p
                class="
                    text-sm
                    text-[#8a8a8a]
                "
            >
                Monitor production output over time.
            </p>

        </div>

    </div>

    <div
        class="mt-6"
    >

        <div
            x-show="hasTrendData"
        >

            <div
                x-ref="productionTrendChart"
                style="height:400px;"
            ></div>

        </div>

        <div
            x-cloak
            x-show="!hasTrendData"
            class="
                h-[400px]

                flex
                items-center
                justify-center

                text-sm
                text-[#8a8a8a]

                border
                border-dashed
                border-[#e8e8e5]

                rounded-2xl
            "
        >

            No production data available.

        </div>

    </div>

</div>