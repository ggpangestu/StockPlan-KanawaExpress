@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-24">

    <div>
        <h1 class="text-3xl font-bold text-[#2c1f16]">Armada Sessions</h1>
        <p class="text-[#5c4432] mt-1">Allocate ready finished goods to each Armada and monitor active sessions.</p>
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

    <form method="POST" action="{{ route('owner.armada-sessions.store') }}" class="bg-white border border-black/5 rounded-3xl overflow-hidden shadow-sm">
        @csrf

        <div class="p-6 border-b border-black/5 flex flex-col md:flex-row gap-4 md:items-end justify-between">
            <div>
                <h2 class="text-xl font-black text-[#2c1f16]">Allocate New Session</h2>
                <p class="text-sm text-[#8a8a8a] mt-1">Choose one Armada and assign available finished-goods batches.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <select name="armada_user_id" class="h-11 rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#2c1f16] font-medium focus:ring-[#7b4a24] focus:border-[#7b4a24]" required>
                    <option value="">Choose Armada</option>
                    @foreach($armadas as $armada)
                        <option value="{{ $armada->id }}">{{ $armada->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="h-11 px-5 rounded-2xl bg-[#2f2f2f] text-white text-sm font-bold hover:opacity-90 transition">
                    Allocate Session
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-black/[0.02] border-b border-black/5">
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase">Product & Batch</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase">Expiry</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase text-right">Ready Stock</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase text-center">Allocate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse($availableGoods as $item)
                        <tr>
                            <td class="py-4 px-6">
                                <div class="font-bold text-[#2c1f16]">{{ $item->menu->name }}</div>
                                <div class="text-xs text-[#8a8a8a] mt-1">PRD-{{ str_pad($item->production_id, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="py-4 px-6 text-sm font-bold text-[#2c1f16]">{{ $item->expired_date->format('d M Y') }}</td>
                            <td class="py-4 px-6 text-right">
                                <span class="text-xl font-black text-[#2c1f16]">{{ $item->current_quantity }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex justify-center">
                                    <input
                                        type="number"
                                        name="items[{{ $item->id }}]"
                                        value="0"
                                        min="0"
                                        max="{{ $item->current_quantity }}"
                                        class="w-24 h-11 rounded-xl border border-black/10 text-center font-black focus:border-[#7b4a24] focus:ring-[#7b4a24]"
                                    >
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center text-gray-500 font-medium">
                                No ready finished goods are available to allocate.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-black/5">
            <textarea name="notes" rows="2" placeholder="Allocation notes" class="w-full rounded-2xl border border-black/10 text-sm focus:border-[#7b4a24] focus:ring-[#7b4a24]"></textarea>
        </div>
    </form>

    <div
        class="bg-white border border-black/5 rounded-3xl overflow-hidden shadow-sm"
        x-data="armadaLiveMonitor('{{ route('owner.armada-sessions.live') }}')"
        x-init="start()"
    >
        <div class="p-6 border-b border-black/5 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-black text-[#2c1f16]">Live Armada Stock</h2>
                <p class="text-sm text-[#8a8a8a] mt-1">Auto-updates from Armada sold counts without refreshing this page.</p>
            </div>
            <div class="text-xs text-[#8a8a8a] font-medium">
                Last update:
                <span x-text="updatedAt || '-'"></span>
            </div>
        </div>

        <div class="p-6 space-y-5">
            <template x-if="loading">
                <div class="py-12 text-center text-gray-500 font-medium">
                    Loading live Armada data...
                </div>
            </template>

            <template x-if="!loading && sessions.length === 0">
                <div class="py-12 text-center text-gray-500 font-medium">
                    No active Armada sessions.
                </div>
            </template>

            <template x-for="session in sessions" :key="session.id">
                <div class="border border-black/5 rounded-2xl overflow-hidden">
                    <div class="p-5 bg-black/[0.02] border-b border-black/5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-lg font-black text-[#2c1f16]" x-text="session.armada"></h3>
                                <span class="inline-flex px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">
                                    Active
                                </span>
                            </div>
                            <p class="text-xs text-[#8a8a8a] mt-1">
                                Session #<span x-text="String(session.id).padStart(4, '0')"></span>
                                · Allocated <span x-text="session.started_at || '-'"></span>
                            </p>
                        </div>

                        <div class="grid grid-cols-3 gap-3 min-w-full lg:min-w-[24rem]">
                            <div class="rounded-2xl bg-white border border-black/5 p-3 text-right">
                                <div class="text-[10px] uppercase font-bold text-[#8a8a8a]">Sent</div>
                                <div class="text-xl font-black text-[#2c1f16]" x-text="session.total_sent"></div>
                            </div>
                            <div class="rounded-2xl bg-white border border-black/5 p-3 text-right">
                                <div class="text-[10px] uppercase font-bold text-[#8a8a8a]">Sold</div>
                                <div class="text-xl font-black text-green-700" x-text="session.total_sold"></div>
                            </div>
                            <div class="rounded-2xl bg-white border border-black/5 p-3 text-right">
                                <div class="text-[10px] uppercase font-bold text-[#8a8a8a]">Left</div>
                                <div class="text-xl font-black text-amber-700" x-text="session.total_remaining"></div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-black/5">
                                    <th class="py-3 px-5 text-xs font-bold text-[#8a8a8a] uppercase">Product</th>
                                    <th class="py-3 px-5 text-xs font-bold text-[#8a8a8a] uppercase">Batch</th>
                                    <th class="py-3 px-5 text-xs font-bold text-[#8a8a8a] uppercase text-right">Sent</th>
                                    <th class="py-3 px-5 text-xs font-bold text-[#8a8a8a] uppercase text-right">Sold</th>
                                    <th class="py-3 px-5 text-xs font-bold text-[#8a8a8a] uppercase text-right">Left</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/5">
                                <template x-for="item in session.items" :key="item.id">
                                    <tr>
                                        <td class="py-3 px-5">
                                            <div class="font-bold text-[#2c1f16] text-sm" x-text="item.product"></div>
                                            <div class="text-xs text-[#8a8a8a] mt-0.5">
                                                Exp <span x-text="item.expired_date"></span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-5 text-sm font-bold text-[#2c1f16]" x-text="item.batch"></td>
                                        <td class="py-3 px-5 text-right font-bold" x-text="item.quantity_sent"></td>
                                        <td class="py-3 px-5 text-right font-bold text-green-700" x-text="item.quantity_sold"></td>
                                        <td class="py-3 px-5 text-right font-bold text-amber-700" x-text="item.remaining"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    function armadaLiveMonitor(url) {
        return {
            url,
            sessions: [],
            updatedAt: null,
            loading: true,
            timer: null,

            start() {
                this.fetchData();
                this.timer = setInterval(() => this.fetchData(), 5000);
            },

            async fetchData() {
                try {
                    const response = await fetch(this.url, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) return;

                    const data = await response.json();

                    this.sessions = data.sessions || [];
                    this.updatedAt = data.updated_at || null;
                } finally {
                    this.loading = false;
                }
            },
        };
    }
</script>
@endsection
