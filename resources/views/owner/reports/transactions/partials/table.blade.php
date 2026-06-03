<!-- TABLE -->
<div
    class="
        bg-white
        border border-[#e8e8e5]
        rounded-3xl
        overflow-hidden
        mt-6
    "
>

    <div
        class="
            px-6 py-5
            border-b border-[#e8e8e5]
        "
    >

        <div
            class="
                flex items-center gap-3
            "
        >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="w-5 h-5 text-violet-500"
            >
                <path d="M3 3h18v18H3z"></path>
                <path d="M3 9h18"></path>
                <path d="M9 21V9"></path>
            </svg>

            <h2
                class="
                    font-semibold
                    text-[#2f2f2f]
                "
            >
                Transactions
            </h2>

        </div>

    </div>

    @if($transactions->isEmpty())

        <div
            class="
                h-80

                flex
                items-center
                justify-center

                text-sm
                text-[#8a8a8a]
            "
        >

            No transactions found.

        </div>

    @else

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr
                        class="
                            bg-[#fafafa]
                            border-b border-[#e8e8e5]
                        "
                    >

                        <th
                            class="
                                px-6 py-4
                                text-left
                                text-xs
                                font-semibold
                                uppercase
                                text-[#8a8a8a]
                            "
                        >
                            Date
                        </th>

                        <th
                            class="
                                px-6 py-4
                                text-left
                                text-xs
                                font-semibold
                                uppercase
                                text-[#8a8a8a]
                            "
                        >
                            Material
                        </th>

                        <th
                            class="
                                px-6 py-4
                                text-center
                                text-xs
                                font-semibold
                                uppercase
                                text-[#8a8a8a]
                            "
                        >
                            Activity Type
                        </th>

                        <th
                            class="
                                px-6 py-4
                                text-center
                                text-xs
                                font-semibold
                                uppercase
                                text-[#8a8a8a]
                            "
                        >
                            Movement
                        </th>

                        <th
                            class="
                                px-6 py-4
                                text-center
                                text-xs
                                font-semibold
                                uppercase
                                text-[#8a8a8a]
                            "
                        >
                            Stock Change
                        </th>

                        <th
                            class="
                                px-6 py-4
                                text-center
                                text-xs
                                font-semibold
                                uppercase
                                text-[#8a8a8a]
                            "
                        >
                            Purchase
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($transactions as $transaction)

                        <tr
                            class="
                                border-b border-[#f3f3f3]
                                hover:bg-violet-50/40
                                transition
                            "
                        >

                            <td class="px-6 py-4">

                                <div
                                    class="
                                        text-sm
                                        text-[#2f2f2f]
                                    "
                                >
                                    {{ $transaction->created_at->format('d M Y') }}
                                </div>

                                <div
                                    class="
                                        text-xs
                                        text-[#8a8a8a]
                                    "
                                >
                                    {{ $transaction->created_at->format('H:i') }}
                                </div>

                            </td>

                            <td class="px-6 py-4">

                                <div
                                    class="
                                        text-sm
                                        font-medium
                                        text-[#2f2f2f]
                                    "
                                >
                                    {{ $transaction->rawMaterial->name }}
                                </div>

                            </td>

                            <td class="px-6 py-4 text-center">

                                @php

                                    $badgeClasses = match ($transaction->type) {

                                        'restock' =>
                                            'bg-sky-50 text-sky-700',

                                        'adjustment_add' =>
                                            'bg-emerald-50 text-emerald-700',

                                        'adjustment_reduce' =>
                                            'bg-rose-50 text-rose-700',

                                        'production_usage' =>
                                            'bg-amber-50 text-amber-700',

                                        default =>
                                            'bg-gray-50 text-gray-700',

                                    };

                                @endphp

                                <span
                                    class="
                                        inline-flex
                                        items-center

                                        px-3 py-1

                                        rounded-full

                                        text-xs
                                        font-medium

                                        {{ $badgeClasses }}
                                    "
                                >

                                    {{ $transaction->type_label }}

                                </span>

                            </td>

                            <td class="px-6 py-4 text-center">

                                @php

                                    $isPositive = in_array(
                                        $transaction->type,
                                        [
                                            'restock',
                                            'adjustment_add',
                                        ]
                                    );

                                @endphp

                                <span
                                    class="
                                        text-sm
                                        font-semibold

                                        {{ $isPositive
                                            ? 'text-emerald-600'
                                            : 'text-rose-600'
                                        }}
                                    "
                                >

                                    {{ $isPositive ? '+' : '-' }}

                                    {{ rtrim(
                                        rtrim(
                                            number_format(
                                                $transaction->quantity,
                                                2,
                                                '.',
                                                ''
                                            ),
                                            '0'
                                        ),
                                        '.'
                                    ) }}

                                    {{ $transaction->rawMaterial->base_unit }}

                                </span>

                            </td>

                            <td class="px-6 py-4 text-center">

                                <div
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2

                                        text-sm
                                    "
                                >

                                    <span class="text-[#8a8a8a]">

                                        {{ rtrim(
                                            rtrim(
                                                number_format(
                                                    $transaction->before_stock,
                                                    2,
                                                    '.',
                                                    ''
                                                ),
                                                '0'
                                            ),
                                            '.'
                                        ) }}

                                        {{ $transaction->rawMaterial->base_unit }}

                                    </span>

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="w-4 h-4 text-[#c4c4c4]"
                                    >
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>

                                    <span
                                        class="
                                            font-medium
                                            text-[#2f2f2f]
                                        "
                                    >

                                        {{ rtrim(
                                            rtrim(
                                                number_format(
                                                    $transaction->after_stock,
                                                    2,
                                                    '.',
                                                    ''
                                                ),
                                                '0'
                                            ),
                                            '.'
                                        ) }}

                                        {{ $transaction->rawMaterial->base_unit }}

                                    </span>

                                </div>

                            </td>

                            <td class="px-6 py-4 text-center">

                                @if($transaction->is_restock)

                                    <span
                                        class="
                                            font-medium
                                            text-violet-600
                                        "
                                    >

                                        Rp {{ number_format($transaction->total_price, 0, ',', '.') }}

                                    </span>

                                @else

                                    <span class="text-[#c4c4c4]">

                                        —

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div
            class="
                px-6 py-4
                border-t border-[#e8e8e5]
            "
        >

            {{ $transactions->links() }}

        </div>

    @endif

</div>