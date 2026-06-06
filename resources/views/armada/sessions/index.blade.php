@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-24">

    <div>
        <h1 class="text-3xl font-bold text-[#2c1f16]">Stok Armada</h1>
        <p class="text-[#5c4432] mt-1">Update produk terjual dan selesaikan sesi agar sisa produk dikirim ke Produksi untuk dicek.</p>
    </div>

    @if(session('success'))
        <div class="rounded-2xl bg-green-100 border border-green-200 text-green-700 px-5 py-4 font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    @if($activeSession)
        <div class="bg-white border border-black/5 rounded-3xl overflow-hidden shadow-sm">
            <div class="p-6 border-b border-black/5 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-black text-[#2c1f16]">Session #{{ str_pad($activeSession->id, 4, '0', STR_PAD_LEFT) }}</h2>
                    <p class="text-sm text-[#8a8a8a] mt-1">Allocated {{ $activeSession->started_at?->format('d M Y H:i') }}</p>
                </div>
                <span class="inline-flex w-fit px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700">
                    Active
                </span>
            </div>

            <form method="POST" action="{{ route('armada.sessions.update-sold', $activeSession) }}">
                @csrf
                @method('PATCH')

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-black/[0.02] border-b border-black/5">
                                <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase">Product</th>
                                <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase text-right">Allocated</th>
                                <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase text-center">Sold</th>
                                <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase text-right">Remaining</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @foreach($activeSession->items as $item)
                                <tr>
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-[#2c1f16]">{{ $item->finishedGood->menu->name }}</div>
                                        <div class="text-xs text-[#8a8a8a] mt-1">
                                            Exp {{ $item->finishedGood->expired_date->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-[#2c1f16]">{{ $item->quantity_sent }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex justify-center">
                                            <input
                                                type="number"
                                                name="sold[{{ $item->id }}]"
                                                value="{{ $item->quantity_sold }}"
                                                min="0"
                                                max="{{ $item->quantity_sent }}"
                                                class="w-24 h-11 rounded-xl border border-black/10 text-center font-black focus:border-[#7b4a24] focus:ring-[#7b4a24]"
                                            >
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-amber-700">
                                        {{ max($item->quantity_sent - $item->quantity_sold, 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-black/5 flex flex-col sm:flex-row justify-end gap-3">
                    <button type="submit" class="h-11 px-5 rounded-2xl border border-black/10 bg-white text-[#2c1f16] text-sm font-bold hover:bg-black/5 transition">
                        Save Sold Counts
                    </button>
                    <button
                        type="submit"
                        formaction="{{ route('armada.sessions.finish', $activeSession) }}"
                        class="h-11 px-5 rounded-2xl bg-[#2f2f2f] text-white text-sm font-bold hover:opacity-90 transition"
                    >
                        Finish Session & Send Unsold to Produksi
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-white border border-dashed border-black/10 rounded-3xl p-12 text-center">
            <h2 class="text-xl font-black text-[#2c1f16]">No Active Session</h2>
            <p class="text-sm text-[#8a8a8a] mt-2">Wait for Owner to allocate finished goods to your Armada account.</p>
        </div>
    @endif

    <div class="bg-white border border-black/5 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-black/5">
            <h2 class="text-xl font-black text-[#2c1f16]">Recent Finished Sessions</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-black/[0.02] border-b border-black/5">
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase">Session</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase text-right">Sent</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase text-right">Sold</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase text-right">Returned</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse($recentSessions as $session)
                        <tr>
                            <td class="py-4 px-6">
                                <div class="font-bold text-[#2c1f16]">Session #{{ str_pad($session->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-xs text-[#8a8a8a] mt-1">{{ $session->finished_at?->format('d M Y H:i') }}</div>
                            </td>
                            <td class="py-4 px-6 text-right font-bold">{{ $session->items->sum('quantity_sent') }}</td>
                            <td class="py-4 px-6 text-right font-bold text-green-700">{{ $session->items->sum('quantity_sold') }}</td>
                            <td class="py-4 px-6 text-right font-bold text-amber-700">{{ $session->items->sum('quantity_returned') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-500 font-medium">No finished sessions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
