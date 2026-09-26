@extends('layouts.armada')

@section('content')

@php
    $userName = auth()->user()->name;

    $nameParts = preg_split('/\s+/', trim($userName));

    $initials = strtoupper(
        mb_substr($nameParts[0] ?? '', 0, 1) .
        (count($nameParts) > 1
            ? mb_substr(end($nameParts), 0, 1)
            : '')
    );

    $sessionDate = $activeSession?->session_date?->format('d M Y')
        ?? $activeSession?->started_at?->format('d M Y');

    $sessionTime = $activeSession?->started_at?->format('H:i');

    $armadaItems = $activeSession
        ? $activeSession->items->map(function ($item) {
            return [
                'id' => (string) $item->id,
                'name' => $item->finishedGood->menu->name,
                'expiresAt' => $item->finishedGood->expired_date?->format('d M Y'),
                'allocated' => (int) $item->quantity_sent,
                'sold' => (int) old(
                    "sold.{$item->id}",
                    $item->quantity_sold
                ),
            ];
        })->values()->all()
        : [];
@endphp


<div
    x-data="armadaPage(
        @js($armadaItems),
        @js($activeSession ? route('armada.sessions.update-sold', $activeSession) : null),
        @js($activeSession ? route('armada.sessions.finish', $activeSession) : null)
    )"
    @keydown.escape.window="finishOpen = false"
    class="min-h-screen bg-[#f5f4f1]"
>

    {{-- =========================================================
         DESKTOP HEADER
    ========================================================== --}}
    <header class="sticky top-0 z-20 hidden border-b border-[#dfddd8] bg-white/90 backdrop-blur md:block">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-8">

            {{-- BRAND --}}
            <div class="flex items-center gap-2.5">
                <div
                    aria-hidden="true"
                    class="grid size-9 place-items-center rounded-lg bg-[#2f2f2f] text-base font-bold text-white"
                >
                    K
                </div>

                <div class="leading-tight">
                    <p class="text-[15px] font-semibold tracking-tight text-[#1f1f1f]">
                        StockPlan
                    </p>

                    <p class="text-xs text-[#737373]">
                        Kanawa Express
                    </p>
                </div>
            </div>

            {{-- USER --}}
            <div class="flex items-center gap-3">

                <div class="flex items-center gap-2 rounded-full bg-[#eeece8] py-1 pl-1 pr-3">
                    <span
                        aria-hidden="true"
                        class="grid size-6 place-items-center rounded-full bg-white text-[10px] font-semibold text-[#2f2f2f] ring-1 ring-[#dedbd6]"
                    >
                        {{ $initials }}
                    </span>

                    <span class="text-xs font-medium text-[#2f2f2f]">
                        {{ $userName }}
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex h-9 items-center gap-2 rounded-lg px-3 text-sm font-medium text-[#737373] transition-colors hover:bg-[#eeece8] hover:text-[#1f1f1f] focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/20"
                    >
                        <i
                            data-lucide="log-out"
                            class="size-4"
                            aria-hidden="true"
                        ></i>

                        <span>Log out</span>
                    </button>
                </form>

            </div>
        </div>
    </header>


    {{-- =========================================================
         MOBILE HEADER
    ========================================================== --}}
    <header class="sticky top-0 z-20 border-b border-[#dfddd8] bg-white/90 backdrop-blur md:hidden">
        <div class="mx-auto flex h-14 items-center justify-between px-4">

            <div class="flex items-center gap-2.5">
                <div
                    aria-hidden="true"
                    class="grid size-8 place-items-center rounded-lg bg-[#2f2f2f] text-sm font-bold text-white"
                >
                    K
                </div>

                <div class="leading-tight">
                    <p class="text-sm font-semibold tracking-tight text-[#1f1f1f]">
                        StockPlan
                    </p>

                    <p class="text-xs text-[#737373]">
                        Kanawa Express
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-1">

                <div class="flex items-center gap-2 rounded-full bg-[#eeece8] py-1 pl-1 pr-2.5">
                    <span
                        aria-hidden="true"
                        class="grid size-6 place-items-center rounded-full bg-white text-[10px] font-semibold text-[#2f2f2f] ring-1 ring-[#dedbd6]"
                    >
                        {{ $initials }}
                    </span>

                    <span class="max-w-[110px] truncate text-xs font-medium text-[#2f2f2f]">
                        {{ $userName }}
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        aria-label="Log out"
                        class="inline-flex size-11 items-center justify-center rounded-lg text-[#737373] transition-colors hover:bg-[#eeece8] hover:text-[#1f1f1f] focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/20"
                    >
                        <i
                            data-lucide="log-out"
                            class="size-4"
                            aria-hidden="true"
                        ></i>
                    </button>
                </form>

            </div>
        </div>
    </header>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}
    <main class="mx-auto max-w-5xl px-4 pb-16 pt-5 md:px-8 md:pt-10">

        {{-- =====================================================
             ACTIVE SESSION
        ====================================================== --}}
        @if($activeSession)

            {{-- =================================================
                 DESKTOP
            ================================================== --}}
            <div class="hidden md:block">

                {{-- SESSION HEADER --}}
                <div class="flex items-end justify-between gap-6">

                    <div>

                        <p class="text-sm font-medium text-[#737373]">
                            Today's selling session
                        </p>

                        <div class="mt-1 flex items-center gap-3">

                            <h1 class="text-3xl font-semibold tracking-tight text-[#1f1f1f]">
                                Session #{{ str_pad($activeSession->id, 4, '0', STR_PAD_LEFT) }}
                            </h1>

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                <span class="size-1.5 rounded-full bg-emerald-600"></span>
                                Active
                            </span>

                        </div>

                        <p class="mt-1.5 text-sm text-[#737373]">
                            Allocated
                            {{ $sessionDate }}
                            @if($sessionTime)
                                , {{ $sessionTime }}
                            @endif
                            · {{ $activeSession->items->count() }} products
                        </p>

                    </div>


                    {{-- SAVE STATUS --}}
                    <div class="pb-1">

                        <p
                            x-show="saveStatus === 'saving'"
                            x-cloak
                            class="flex items-center gap-1.5 text-xs text-[#737373]"
                        >
                            <i
                                data-lucide="loader-circle"
                                class="size-3.5 animate-spin"
                            ></i>
                            Saving sales…
                        </p>

                        <p
                            x-show="dirty"
                            x-cloak
                            class="flex items-center gap-1.5 text-xs text-amber-600"
                        >
                            <span class="size-1.5 rounded-full bg-amber-500"></span>
                            Unsaved changes
                        </p>

                        <p
                            x-show="!dirty && saveStatus === 'saved'"
                            x-cloak
                            class="flex items-center gap-1.5 text-xs text-emerald-700"
                        >
                            <i
                                data-lucide="circle-check"
                                class="size-3.5"
                            ></i>
                            Sales saved
                        </p>

                        <p
                            x-show="!dirty && saveStatus !== 'saved' && saveStatus !== 'saving'"
                            x-cloak
                            class="text-xs text-[#737373]"
                        >
                            All sales saved
                        </p>

                    </div>

                </div>


                {{-- PRODUCT TABLE --}}
                <section
                    class="mt-6 overflow-hidden rounded-2xl border border-[#dfddd8] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04),0_8px_24px_-12px_rgba(0,0,0,0.08)]"
                >

                    <form
                        x-ref="desktopForm"
                        method="POST"
                        action="{{ route('armada.sessions.update-sold', $activeSession) }}"
                        @submit="beginSave()"
                    >
                        @csrf
                        @method('PATCH')

                        <table class="w-full text-sm">

                            <caption class="sr-only">
                                Products assigned to this session
                            </caption>

                            <thead>

                                <tr class="border-y border-[#dfddd8] bg-[#f3f2ef] text-[11px] font-semibold uppercase tracking-wider text-[#737373]">

                                    <th class="px-6 py-3 text-left font-semibold">
                                        Product
                                    </th>

                                    <th class="w-32 px-6 py-3 text-right font-semibold">
                                        Allocated
                                    </th>

                                    <th class="w-48 px-6 py-3 text-center font-semibold">
                                        Sold
                                    </th>

                                    <th class="w-44 px-6 py-3 text-right font-semibold">
                                        Remaining
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-[#e8e6e1]">

                                @foreach($activeSession->items as $item)

                                    <tr
                                        :class="remaining('{{ $item->id }}') === 0 ? 'bg-emerald-50/40' : ''"
                                        class="transition-colors"
                                    >

                                        <th
                                            scope="row"
                                            class="px-6 py-4 text-left align-middle font-normal"
                                        >
                                            <p class="text-[15px] font-semibold text-[#1f1f1f]">
                                                {{ $item->finishedGood->menu->name }}
                                            </p>

                                            @if($item->finishedGood->expired_date)
                                                <p class="mt-0.5 text-xs text-[#737373]">
                                                    Exp {{ $item->finishedGood->expired_date->format('d M Y') }}
                                                </p>
                                            @endif
                                        </th>


                                        <td class="px-6 text-right align-middle text-base font-medium tabular-nums text-[#1f1f1f]">
                                            {{ $item->quantity_sent }}
                                        </td>


                                        <td class="px-6 align-middle">

                                            <div class="relative flex justify-center">

                                                <div class="flex items-center gap-2 pl-10">

                                                    <input
                                                        type="text"
                                                        inputmode="numeric"
                                                        pattern="[0-9]*"
                                                        autocomplete="off"
                                                        name="sold[{{ $item->id }}]"
                                                        :value="item('{{ $item->id }}').sold"
                                                        @input="setSold('{{ $item->id }}', $event.target.value)"
                                                        @focus="$event.target.select()"
                                                        class="h-10 w-20 rounded-lg border border-[#dedbd6] bg-white text-center text-base font-semibold tabular-nums shadow-sm transition-colors hover:border-[#aaa59d] focus:border-[#2f2f2f] focus:outline-none focus:ring-3 focus:ring-[#2f2f2f]/10"
                                                    >

                                                    <span class="w-8 text-xs tabular-nums text-[#737373]">
                                                        / {{ $item->quantity_sent }}
                                                    </span>

                                                </div>

                                            </div>

                                        </td>


                                        <td class="px-6 text-right align-middle">

                                            <div class="flex items-center justify-end gap-2">

                                                <span
                                                    x-show="remaining('{{ $item->id }}') === 0"
                                                    x-cloak
                                                    class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700"
                                                >
                                                    <i
                                                        data-lucide="check"
                                                        class="size-3"
                                                    ></i>
                                                    Sold out
                                                </span>

                                                <span
                                                    class="text-lg font-semibold tabular-nums"
                                                    :class="remaining('{{ $item->id }}') === 0 ? 'text-emerald-700' : 'text-amber-600'"
                                                    x-text="remaining('{{ $item->id }}')"
                                                ></span>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>


                        {{-- DESKTOP FOOTER --}}
                        <div class="flex items-center justify-between gap-6 border-t border-[#dfddd8] bg-[#f3f2ef]/70 px-6 py-5">

                            {{-- SUMMARY --}}
                            <div class="flex items-center gap-6">

                                <dl class="flex divide-x divide-[#dfddd8] rounded-xl border border-[#dfddd8] bg-white">

                                    <div class="px-4 py-2">
                                        <dt class="text-[11px] font-medium text-[#737373]">
                                            Allocated
                                        </dt>

                                        <dd class="text-lg font-semibold leading-tight tabular-nums text-[#1f1f1f]">
                                            <span x-text="totals().allocated"></span>
                                        </dd>
                                    </div>

                                    <div class="px-4 py-2">
                                        <dt class="text-[11px] font-medium text-[#737373]">
                                            Sold
                                        </dt>

                                        <dd class="text-lg font-semibold leading-tight tabular-nums text-emerald-700">
                                            <span x-text="totals().sold"></span>
                                        </dd>
                                    </div>

                                    <div class="px-4 py-2">
                                        <dt class="text-[11px] font-medium text-[#737373]">
                                            Remaining
                                        </dt>

                                        <dd class="text-lg font-semibold leading-tight tabular-nums text-amber-600">
                                            <span x-text="totals().remaining"></span>
                                        </dd>
                                    </div>

                                </dl>


                                {{-- PROGRESS --}}
                                <div class="w-36">

                                    <div class="flex justify-between text-[11px] text-[#737373]">
                                        <span>Sold</span>

                                        <span
                                            class="tabular-nums"
                                            x-text="progress() + '%'"
                                        ></span>
                                    </div>

                                    <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-[#e6e4df]">

                                        <div
                                            class="h-full rounded-full bg-emerald-600 transition-[width] duration-300"
                                            :style="`width: ${progress()}%`"
                                        ></div>

                                    </div>

                                </div>

                            </div>


                            {{-- ACTIONS --}}
                            <div class="flex items-center gap-3">

                                <button
                                    type="button"
                                    @click="openFinish('desktop')"
                                    class="inline-flex h-11 items-center gap-2 rounded-xl border border-[#dedbd6] bg-white px-4 text-sm font-semibold text-[#2f2f2f] transition-colors hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700 focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/10"
                                >
                                    <i
                                        data-lucide="undo-2"
                                        class="size-4"
                                    ></i>

                                    Finish Session & Return Unsold
                                </button>

                                <span
                                    aria-hidden="true"
                                    class="h-8 w-px bg-[#dedbd6]"
                                ></span>

                                <button
                                    type="submit"
                                    class="inline-flex h-11 min-w-32 items-center justify-center rounded-xl bg-[#2f2f2f] px-6 text-sm font-semibold text-white transition-colors hover:bg-[#1f1f1f] focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/20 disabled:opacity-60"
                                    :disabled="saveStatus === 'saving'"
                                >
                                    <span x-text="saveStatus === 'saving' ? 'Saving…' : 'Save Sales'"></span>
                                </button>

                            </div>

                        </div>

                    </form>

                </section>

            </div>


            {{-- =================================================
                 MOBILE
            ================================================== --}}
            <div class="md:hidden">

                <div>

                    <p class="text-xs font-medium text-[#737373]">
                        Today's selling session
                    </p>

                    <div class="mt-0.5 flex items-center justify-between">

                        <h1 class="text-2xl font-semibold tracking-tight text-[#1f1f1f]">
                            Session #{{ str_pad($activeSession->id, 4, '0', STR_PAD_LEFT) }}
                        </h1>

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            <span class="size-1.5 rounded-full bg-emerald-600"></span>
                            Active
                        </span>

                    </div>

                    <p class="mt-0.5 text-xs text-[#737373]">
                        Allocated
                        {{ $sessionDate }}
                        @if($sessionTime)
                            , {{ $sessionTime }}
                        @endif
                    </p>

                </div>


                <form
                    x-ref="mobileForm"
                    method="POST"
                    action="{{ route('armada.sessions.update-sold', $activeSession) }}"
                    @submit="beginSave()"
                >
                    @csrf
                    @method('PATCH')


                    {{-- PRODUCT CARDS --}}
                    <ul class="mt-4 flex flex-col gap-3">

                        @foreach($activeSession->items as $item)

                            <li
                                class="rounded-2xl border border-[#dedbd6] bg-white p-4 shadow-[0_1px_2px_rgba(0,0,0,0.04)] transition-colors"
                                :class="remaining('{{ $item->id }}') === 0 ? 'border-emerald-200 bg-emerald-50/40' : ''"
                            >

                                <div class="flex items-start justify-between gap-3">

                                    <div>

                                        <h3 class="text-[17px] leading-snug font-semibold text-[#1f1f1f]">
                                            {{ $item->finishedGood->menu->name }}
                                        </h3>

                                        @if($item->finishedGood->expired_date)
                                            <p class="mt-0.5 text-xs text-[#737373]">
                                                Exp {{ $item->finishedGood->expired_date->format('d M Y') }}
                                            </p>
                                        @endif

                                    </div>


                                    <span
                                        x-show="remaining('{{ $item->id }}') === 0"
                                        x-cloak
                                        class="mt-0.5 inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700"
                                    >
                                        <i
                                            data-lucide="check"
                                            class="size-3"
                                        ></i>

                                        Sold out
                                    </span>

                                </div>


                                {{-- ALLOCATED / REMAINING --}}
                                <dl class="mt-3 grid grid-cols-2 gap-2">

                                    <div class="rounded-xl bg-[#eeece8] px-3 py-2">

                                        <dt class="text-[11px] font-medium text-[#737373]">
                                            Allocated
                                        </dt>

                                        <dd class="text-xl leading-tight font-semibold tabular-nums text-[#1f1f1f]">
                                            {{ $item->quantity_sent }}
                                        </dd>

                                    </div>


                                    <div
                                        class="rounded-xl px-3 py-2"
                                        :class="remaining('{{ $item->id }}') === 0 ? 'bg-emerald-50' : 'bg-amber-50'"
                                    >

                                        <dt class="text-[11px] font-medium text-[#737373]">
                                            Remaining
                                        </dt>

                                        <dd
                                            class="text-xl leading-tight font-semibold tabular-nums"
                                            :class="remaining('{{ $item->id }}') === 0 ? 'text-emerald-700' : 'text-amber-600'"
                                            x-text="remaining('{{ $item->id }}')"
                                        ></dd>

                                    </div>

                                </dl>


                                {{-- SOLD STEPPER --}}
                                <div class="mt-3 flex items-center justify-between border-t border-[#e5e3de] pt-3">

                                    <p class="text-sm font-semibold text-[#1f1f1f]">
                                        Sold
                                    </p>

                                    <div class="flex items-center gap-1">

                                        <button
                                            type="button"
                                            @click="decrement('{{ $item->id }}')"
                                            :disabled="item('{{ $item->id }}').sold <= 0"
                                            class="grid size-9 shrink-0 place-items-center rounded-lg border border-[#dedbd6] bg-white text-[#1f1f1f] transition active:scale-95 active:bg-[#eeece8] focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/10 disabled:opacity-35"
                                            aria-label="Decrease sold quantity"
                                        >
                                            <i
                                                data-lucide="minus"
                                                class="size-3.5"
                                                stroke-width="2.5"
                                            ></i>
                                        </button>

                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            pattern="[0-9]*"
                                            autocomplete="off"
                                            name="sold[{{ $item->id }}]"
                                            :value="item('{{ $item->id }}').sold"
                                            @input="setSold('{{ $item->id }}', $event.target.value)"
                                            @focus="$event.target.select()"
                                            class="h-9 w-12 rounded-lg bg-transparent text-center text-xl font-semibold tabular-nums text-[#1f1f1f] focus:bg-[#eeece8] focus:outline-none"
                                        >

                                        <button
                                            type="button"
                                            @click="increment('{{ $item->id }}')"
                                            :disabled="item('{{ $item->id }}').sold >= {{ $item->quantity_sent }}"
                                            class="grid size-9 shrink-0 place-items-center rounded-lg border border-[#2f2f2f] bg-[#2f2f2f] text-white transition active:scale-95 active:bg-[#1f1f1f] focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/20 disabled:opacity-35"
                                            aria-label="Increase sold quantity"
                                        >
                                            <i
                                                data-lucide="plus"
                                                class="size-3.5"
                                                stroke-width="2.5"
                                            ></i>
                                        </button>

                                    </div>

                                </div>

                            </li>

                        @endforeach

                    </ul>


                    {{-- MOBILE SUMMARY --}}
                    <div class="mt-4">

                        <dl class="grid grid-cols-3 divide-x divide-[#dedbd6] rounded-2xl border border-[#dedbd6] bg-white py-3 text-center">

                            <div>
                                <dt class="text-[11px] font-medium text-[#737373]">
                                    Allocated
                                </dt>

                                <dd class="text-lg font-semibold tabular-nums text-[#1f1f1f]">
                                    <span x-text="totals().allocated"></span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-[11px] font-medium text-[#737373]">
                                    Sold
                                </dt>

                                <dd class="text-lg font-semibold tabular-nums text-emerald-700">
                                    <span x-text="totals().sold"></span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-[11px] font-medium text-[#737373]">
                                    Remaining
                                </dt>

                                <dd class="text-lg font-semibold tabular-nums text-amber-600">
                                    <span x-text="totals().remaining"></span>
                                </dd>
                            </div>

                        </dl>

                    </div>


                    {{-- MOBILE SAVE --}}
                    <div class="sticky bottom-0 z-10 -mx-4 mt-4 border-t border-[#dedbd6] bg-[#f5f4f1]/95 px-4 pb-4 pt-3 backdrop-blur">

                        <p
                            x-show="saveStatus === 'saving'"
                            x-cloak
                            class="mb-2 flex items-center justify-center gap-1.5 text-xs text-[#737373]"
                        >
                            <i
                                data-lucide="loader-circle"
                                class="size-3.5 animate-spin"
                            ></i>

                            Saving sales…
                        </p>

                        <p
                            x-show="dirty"
                            x-cloak
                            class="mb-2 flex items-center justify-center gap-1.5 text-xs text-amber-600"
                        >
                            <span class="size-1.5 rounded-full bg-amber-500"></span>
                            Unsaved changes
                        </p>

                        <p
                            x-show="!dirty && saveStatus === 'saved'"
                            x-cloak
                            class="mb-2 flex items-center justify-center gap-1.5 text-xs text-emerald-700"
                        >
                            <i
                                data-lucide="circle-check"
                                class="size-3.5"
                            ></i>

                            Sales saved
                        </p>

                        <p
                            x-show="!dirty && saveStatus !== 'saved' && saveStatus !== 'saving'"
                            x-cloak
                            class="mb-2 text-center text-xs text-[#737373]"
                        >
                            All sales saved
                        </p>


                        <button
                            type="submit"
                            class="h-14 w-full rounded-2xl bg-[#2f2f2f] text-base font-semibold text-white transition active:scale-[0.99] active:bg-[#1f1f1f] focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/20 disabled:opacity-60"
                            :disabled="saveStatus === 'saving'"
                        >
                            <span x-text="saveStatus === 'saving' ? 'Saving…' : 'Save Sales'"></span>
                        </button>

                    </div>


                    {{-- FINISH --}}
                    <div class="mt-6 rounded-2xl border border-dashed border-[#d7d4ce] p-4">

                        <p class="text-sm font-semibold text-[#1f1f1f]">
                            Done selling for today?
                        </p>

                        <p class="mt-0.5 text-xs leading-relaxed text-[#737373]">
                            Unsold products go back to Production.
                        </p>

                        <button
                            type="button"
                            @click="openFinish('mobile')"
                            class="mt-3 inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl border border-[#dedbd6] bg-white text-sm font-semibold text-[#2f2f2f] transition-colors active:bg-[#eeece8] focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/10"
                        >
                            <i
                                data-lucide="undo-2"
                                class="size-4"
                            ></i>

                            Finish Session & Return Unsold
                        </button>

                    </div>

                </form>

            </div>

        @else

            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}
            <div class="mb-4">
                <p class="text-sm font-medium text-[#737373]">
                    Today's selling session
                </p>
            </div>


            {{-- DESKTOP EMPTY --}}
            <section class="hidden flex-col items-center rounded-2xl border border-dashed border-[#d7d4ce] bg-white px-8 py-16 text-center md:flex">

                <div class="grid size-12 place-items-center rounded-full bg-[#eeece8] text-[#737373]">
                    <i
                        data-lucide="package-open"
                        class="size-6"
                    ></i>
                </div>

                <h2 class="mt-4 text-lg font-semibold tracking-tight text-[#1f1f1f]">
                    No active selling session
                </h2>

                <p class="mt-1 max-w-xs text-sm leading-relaxed text-[#737373]">
                    You don't have any products assigned for selling yet.
                </p>

            </section>


            {{-- MOBILE EMPTY --}}
            <section class="flex flex-col items-center rounded-2xl border border-dashed border-[#d7d4ce] bg-white px-6 py-12 text-center md:hidden">

                <div class="grid size-12 place-items-center rounded-full bg-[#eeece8] text-[#737373]">
                    <i
                        data-lucide="package-open"
                        class="size-6"
                    ></i>
                </div>

                <h2 class="mt-4 text-lg font-semibold tracking-tight text-[#1f1f1f]">
                    No active selling session
                </h2>

                <p class="mt-1 max-w-xs text-sm leading-relaxed text-[#737373]">
                    You don't have any products assigned for selling yet.
                </p>

            </section>

        @endif


        {{-- =====================================================
             RECENT SESSIONS — DESKTOP
        ====================================================== --}}
        <section class="mt-10 hidden overflow-hidden rounded-2xl border border-[#dedbd6] bg-white/70 md:block">

            <div class="flex items-baseline justify-between px-6 pb-4 pt-5">

                <h2 class="text-sm font-semibold text-[#1f1f1f]">
                    Recent finished sessions
                </h2>

                <p class="text-xs text-[#737373]">
                    Last {{ $recentSessions->count() }} sessions
                </p>

            </div>


            <table class="w-full text-sm">

                <thead>

                    <tr class="border-y border-[#dedbd6] text-[11px] uppercase tracking-wider text-[#737373]">

                        <th class="px-6 py-2.5 text-left font-semibold">
                            Session
                        </th>

                        <th class="px-6 py-2.5 text-left font-semibold">
                            Date
                        </th>

                        <th class="w-28 px-6 py-2.5 text-right font-semibold">
                            Sent
                        </th>

                        <th class="w-28 px-6 py-2.5 text-right font-semibold">
                            Sold
                        </th>

                        <th class="w-32 px-6 py-2.5 text-right font-semibold">
                            Returned
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#e8e6e1] text-[#737373]">

                    @forelse($recentSessions as $session)

                        <tr>

                            <th
                                scope="row"
                                class="px-6 py-3 text-left font-medium text-[#1f1f1f]"
                            >
                                Session #{{ str_pad($session->id, 4, '0', STR_PAD_LEFT) }}
                            </th>

                            <td class="px-6 py-3">
                                {{ $session->finished_at?->format('d M Y') }}
                            </td>

                            <td class="px-6 py-3 text-right tabular-nums text-[#1f1f1f]">
                                {{ $session->items->sum('quantity_sent') }}
                            </td>

                            <td class="px-6 py-3 text-right tabular-nums">
                                {{ $session->items->sum('quantity_sold') }}
                            </td>

                            <td class="px-6 py-3 text-right tabular-nums">
                                {{ $session->items->sum('quantity_returned') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="px-6 py-10 text-center text-sm text-[#737373]"
                            >
                                No finished sessions yet.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </section>


        {{-- =====================================================
             RECENT SESSIONS — MOBILE
        ====================================================== --}}
        <section class="mt-8 md:hidden">

            <h2 class="px-1 text-sm font-semibold text-[#737373]">
                Recent finished sessions
            </h2>

            <ul class="mt-2.5 divide-y divide-[#e8e6e1] overflow-hidden rounded-2xl border border-[#dedbd6] bg-white/70">

                @forelse($recentSessions as $session)

                    <li class="flex items-center justify-between gap-3 px-4 py-3">

                        <div>

                            <p class="text-sm font-semibold text-[#1f1f1f]">
                                Session #{{ str_pad($session->id, 4, '0', STR_PAD_LEFT) }}
                            </p>

                            <p class="text-xs text-[#737373]">
                                {{ $session->finished_at?->format('d M Y') }}
                            </p>

                        </div>


                        <dl class="flex gap-4 text-right">

                            <div>
                                <dt class="text-[10px] text-[#737373]">
                                    Sent
                                </dt>

                                <dd class="text-sm font-semibold tabular-nums">
                                    {{ $session->items->sum('quantity_sent') }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-[10px] text-[#737373]">
                                    Sold
                                </dt>

                                <dd class="text-sm font-semibold tabular-nums">
                                    {{ $session->items->sum('quantity_sold') }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-[10px] text-[#737373]">
                                    Returned
                                </dt>

                                <dd class="text-sm font-semibold tabular-nums">
                                    {{ $session->items->sum('quantity_returned') }}
                                </dd>
                            </div>

                        </dl>

                    </li>

                @empty

                    <li class="px-4 py-10 text-center text-sm text-[#737373]">
                        No finished sessions yet.
                    </li>

                @endforelse

            </ul>

        </section>

    </main>


    {{-- =========================================================
         FINISH CONFIRMATION
    ========================================================== --}}
    @if($activeSession)

        <div
            x-show="finishOpen"
            x-cloak
            x-transition.opacity
            @click.self="finishOpen = false"
            class="fixed inset-0 z-50 flex items-end bg-black/40 md:items-center md:justify-center md:p-6"
        >

            <div
                x-show="finishOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 scale-[0.98]"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-[0.98]"
                role="alertdialog"
                aria-modal="true"
                class="w-full rounded-t-3xl bg-white px-5 pb-6 pt-3 shadow-xl md:max-w-md md:rounded-2xl md:p-6"
            >

                {{-- Mobile handle --}}
                <div class="mx-auto mb-4 h-1 w-10 rounded-full bg-[#dedbd6] md:hidden"></div>


                <div class="flex size-10 items-center justify-center rounded-full bg-amber-50 text-amber-700">

                    <i
                        data-lucide="undo-2"
                        class="size-5"
                    ></i>

                </div>


                <h2 class="mt-4 text-lg font-semibold tracking-tight text-[#1f1f1f]">
                    Finish this selling session?
                </h2>


                <div class="mt-1.5 text-sm leading-relaxed text-[#737373]">

                    <template x-if="totals().remaining > 0">

                        <p>
                            You still have
                            <span
                                class="font-semibold text-[#1f1f1f]"
                                x-text="totals().remaining"
                            ></span>
                            unsold products.
                            These products will be returned to Production.
                        </p>

                    </template>


                    <template x-if="totals().remaining === 0">

                        <p>
                            Everything has been sold.
                            Nothing will be returned to Production.
                        </p>

                    </template>

                </div>


                <div
                    x-show="totals().remaining > 0"
                    x-cloak
                    class="mt-4 divide-y divide-[#dfddd8] overflow-hidden rounded-xl border border-[#dedbd6] bg-[#f3f2ef]/70 text-sm"
                >

                    @foreach($activeSession->items as $item)

                        <div
                            x-show="remaining('{{ $item->id }}') > 0"
                            class="flex items-center justify-between px-3.5 py-2.5"
                        >

                            <span class="font-medium text-[#1f1f1f]">
                                {{ $item->finishedGood->menu->name }}
                            </span>

                            <span class="tabular-nums text-[#737373]">

                                <span
                                    class="font-semibold text-amber-600"
                                    x-text="remaining('{{ $item->id }}')"
                                ></span>

                                returned

                            </span>

                        </div>

                    @endforeach

                </div>


                <p class="mt-3 text-xs text-[#737373]">
                    Your current sold counts will be saved.
                    This can't be undone.
                </p>


                <div class="mt-6 flex flex-col-reverse gap-3 md:flex-row md:justify-end">

                    <button
                        type="button"
                        @click="finishOpen = false"
                        class="h-12 rounded-xl border border-[#dedbd6] bg-white text-base font-semibold text-[#2f2f2f] transition-colors hover:bg-[#f3f2ef] focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/10 md:h-10 md:px-4 md:text-sm"
                    >
                        Cancel
                    </button>


                    <button
                        type="button"
                        @click="submitFinish()"
                        :disabled="finishing"
                        class="h-12 rounded-xl bg-[#2f2f2f] text-base font-semibold text-white transition-colors hover:bg-[#1f1f1f] focus:outline-none focus:ring-2 focus:ring-[#2f2f2f]/20 disabled:opacity-60 md:h-10 md:px-4 md:text-sm"
                    >
                        <span x-text="finishing ? 'Finishing…' : 'Finish Session'"></span>
                    </button>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection


@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {

        Alpine.data(
            'armadaPage',
            (
                initialItems,
                saveUrl,
                finishUrl
            ) => ({

                items: initialItems,

                saveUrl,
                finishUrl,

                finishOpen: false,
                finishSource: null,
                finishing: false,

                saveStatus: 'idle',
                dirty: false,

                overLimit: {},


                init() {
                    this.items = this.items.map(item => ({
                        ...item,

                        sold: this.clamp(
                            Number(item.sold ?? 0),
                            0,
                            Number(item.allocated)
                        )
                    }));
                },


                clamp(value, min, max) {
                    const number = Number.isFinite(Number(value))
                        ? Math.trunc(Number(value))
                        : 0;

                    return Math.min(
                        Math.max(number, min),
                        max
                    );
                },


                item(id) {
                    return this.items.find(
                        item => String(item.id) === String(id)
                    );
                },


                setSold(id, rawValue) {

                    const item = this.item(id);

                    if (!item) {
                        return;
                    }

                    const raw = String(rawValue ?? '')
                        .replace(/\D/g, '');

                    const numeric = raw === ''
                        ? 0
                        : Number(raw);

                    const allocated = Number(item.allocated);
                    const exceeded = numeric > allocated;

                    if (exceeded && !this.overLimit[id]) {
                        Alpine.store('toastManager').addErrorToast(
                            `Maximum ${allocated} allocated for ${item.name}.`
                        );
                    }

                    this.overLimit[id] = exceeded;

                    item.sold = this.clamp(
                        numeric,
                        0,
                        allocated
                    );

                    this.dirty = true;

                    if (this.saveStatus === 'saved') {
                        this.saveStatus = 'idle';
                    }
                },

                increment(id) {

                    const item = this.item(id);

                    if (!item) {
                        return;
                    }

                    this.setSold(
                        id,
                        Number(item.sold) + 1
                    );
                },


                decrement(id) {

                    const item = this.item(id);

                    if (!item) {
                        return;
                    }

                    this.setSold(
                        id,
                        Number(item.sold) - 1
                    );
                },


                remaining(id) {

                    const item = this.item(id);

                    if (!item) {
                        return 0;
                    }

                    return Math.max(
                        Number(item.allocated) -
                        Number(item.sold),
                        0
                    );
                },


                totals() {

                    return this.items.reduce(
                        (total, item) => {

                            const allocated =
                                Number(item.allocated);

                            const sold =
                                this.clamp(
                                    Number(item.sold),
                                    0,
                                    allocated
                                );

                            total.allocated += allocated;
                            total.sold += sold;
                            total.remaining += Math.max(
                                allocated - sold,
                                0
                            );

                            return total;

                        },
                        {
                            allocated: 0,
                            sold: 0,
                            remaining: 0
                        }
                    );
                },


                progress() {

                    const totals = this.totals();

                    if (!totals.allocated) {
                        return 0;
                    }

                    return Math.round(
                        (totals.sold / totals.allocated) * 100
                    );
                },


                beginSave() {
                    this.saveStatus = 'saving';
                },


                openFinish(source) {
                    this.finishSource = source;
                    this.finishOpen = true;
                },


                submitFinish() {

                    if (this.finishing) {
                        return;
                    }

                    const form =
                        this.finishSource === 'mobile'
                            ? this.$refs.mobileForm
                            : this.$refs.desktopForm;

                    if (!form) {
                        return;
                    }

                    this.finishing = true;

                    form.action = this.finishUrl;
                    form.submit();
                }

            })
        );

    });
</script>
@endpush