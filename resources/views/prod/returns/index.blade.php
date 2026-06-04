@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-24">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[#2c1f16]">Retur Armada</h1>
            <p class="text-[#5c4432] mt-1">Cek produk yang tidak terjual sebelum masuk kembali ke stok jadi.</p>
        </div>

        <form method="GET">
            <select name="filter" onchange="this.form.submit()" class="h-11 rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#2c1f16] shadow-sm font-medium focus:ring-[#7b4a24] focus:border-[#7b4a24]">
                <option value="pending" {{ $filter === 'pending' ? 'selected' : '' }}>Pending Check</option>
                <option value="ready" {{ $filter === 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="expired_damaged" {{ $filter === 'expired_damaged' ? 'selected' : '' }}>Expired / Damaged</option>
                <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All</option>
            </select>
        </form>
    </div>

    @if(session('success'))
        <div class="rounded-2xl bg-green-100 border border-green-200 text-green-700 px-5 py-4 font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm font-medium">
            {{ session('error') ?? $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-black/5 shadow-sm">
            <p class="text-sm font-bold text-[#8a8a8a] uppercase tracking-wider">Pending Check</p>
            <h3 class="text-3xl font-black text-amber-600 mt-2">{{ number_format($stats['pending'], 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-black/5 shadow-sm">
            <p class="text-sm font-bold text-[#8a8a8a] uppercase tracking-wider">Returned Ready</p>
            <h3 class="text-3xl font-black text-green-700 mt-2">{{ number_format($stats['ready'], 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-black/5 shadow-sm">
            <p class="text-sm font-bold text-[#8a8a8a] uppercase tracking-wider">Expired / Damaged</p>
            <h3 class="text-3xl font-black text-red-600 mt-2">{{ number_format($stats['expired_damaged'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white border border-black/5 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/[0.02] border-b border-black/5">
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider">Product & Batch</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider">Armada</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider">Expiry</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider text-right">Returned</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider text-center">Status</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse($returnChecks as $check)
                        @php
                            $finishedGood = $check->finishedGood;
                            $isExpired = $finishedGood->expired_date->lt(today());
                        @endphp

                        <tr class="hover:bg-black/[0.01] transition duration-150">
                            <td class="py-4 px-6">
                                <div class="font-bold text-[#2c1f16]">{{ $finishedGood->menu->name }}</div>
                                <div class="text-xs text-[#8a8a8a] mt-1">
                                    PRD-{{ str_pad($finishedGood->production_id, 4, '0', STR_PAD_LEFT) }}
                                    · Session #{{ str_pad($check->sessionItem->armada_session_id, 4, '0', STR_PAD_LEFT) }}
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="font-bold text-[#2c1f16] text-sm">{{ $check->armada->name }}</div>
                                <div class="text-xs text-[#8a8a8a] mt-1">{{ $check->created_at->format('d M Y H:i') }}</div>
                            </td>

                            <td class="py-4 px-6">
                                <span class="font-bold text-sm {{ $isExpired ? 'text-red-600' : 'text-[#2c1f16]' }}">
                                    {{ $finishedGood->expired_date->format('d M Y') }}
                                </span>
                            </td>

                            <td class="py-4 px-6 text-right">
                                <span class="text-xl font-black text-[#2c1f16]">{{ $check->quantity }}</span>
                                <span class="text-xs text-[#8a8a8a] font-medium ml-1">Porsi</span>
                            </td>

                            <td class="py-4 px-6 text-center">
                                @if($check->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                                        Pending Check
                                    </span>
                                @elseif($check->status === 'ready')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700">
                                        Ready
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600">
                                        Expired / Damaged
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6">
                                @if($check->status === 'pending')
                                    <div class="flex flex-col lg:flex-row justify-end gap-2">
                                        <form method="POST" action="{{ route('produksi.returns.update', $check) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="ready">
                                            <button type="submit" class="h-10 px-4 rounded-xl bg-green-600 text-white text-xs font-bold hover:bg-green-700 transition">
                                                Ready
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('produksi.returns.update', $check) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="expired_damaged">
                                            <button type="submit" class="h-10 px-4 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition">
                                                Expired / Damaged
                                            </button>
                                        </form>
                                    </div>
                                @elseif($check->status === 'expired_damaged' && $check->rejectedFinishedGood && $check->rejectedFinishedGood->current_quantity > 0)
                                    <form method="POST" action="{{ route('produksi.returns.dispose', $check) }}" class="flex justify-end">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="h-10 px-4 rounded-xl bg-[#2f2f2f] text-white text-xs font-bold hover:opacity-90 transition">
                                            Dispose
                                        </button>
                                    </form>
                                @else
                                    <div class="text-right text-xs text-[#8a8a8a]">
                                        Checked {{ $check->checked_at?->format('d M Y H:i') }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-gray-500 font-medium">
                                No returned products match this filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
