@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-24">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[#2c1f16]">Stok Jadi (Etalase)</h1>
            <p class="text-[#5c4432] mt-1">Pantau produk yang siap didistribusikan dan masa simpannya.</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET">
                <select name="filter" onchange="this.form.submit()" class="h-11 rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#2c1f16] shadow-sm font-medium focus:ring-[#7b4a24] focus:border-[#7b4a24]">
                    <option value="available" {{ $filter === 'available' ? 'selected' : '' }}>Tersedia (Ready)</option>
                    <option value="empty" {{ $filter === 'empty' ? 'selected' : '' }}>Habis Terjual</option>
                    <option value="expired" {{ $filter === 'expired' ? 'selected' : '' }}>Kedaluwarsa (Basi)</option>
                </select>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-black/5 shadow-sm flex items-center gap-5 relative overflow-hidden">
            <div class="w-14 h-14 rounded-2xl bg-[#f5efe6] text-[#7b4a24] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-[#8a8a8a] uppercase tracking-wider mb-1">Total Porsi Tersedia</p>
                <h3 class="text-3xl font-black text-[#2c1f16]">{{ number_format($stats['total_available'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-black/5 shadow-sm flex items-center gap-5 relative overflow-hidden">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-[#8a8a8a] uppercase tracking-wider mb-1">Hampir Kedaluwarsa</p>
                <h3 class="text-3xl font-black text-amber-600">{{ $stats['expiring_soon'] }} <span class="text-sm font-bold text-amber-500">Batch</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-black/5 shadow-sm flex items-center gap-5 relative overflow-hidden">
            <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-[#8a8a8a] uppercase tracking-wider mb-1">Total Porsi Basi</p>
                <h3 class="text-3xl font-black text-red-600">{{ number_format($stats['total_expired'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white border border-black/5 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/[0.02] border-b border-black/5">
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider">Produk & Batch</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider">Tgl Produksi</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider">Kedaluwarsa</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider text-right">Sisa Stok</th>
                        <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse($goods as $item)
                        @php
                            // Logika Smart Expired Indicator
                            $daysRemaining = \Carbon\Carbon::today()->diffInDays($item->expired_date, false);
                            $isExpired = $daysRemaining < 0 || $item->status === 'expired';
                            $isExpiringSoon = $daysRemaining >= 0 && $daysRemaining <= 1; // 0 (hari ini) atau 1 (besok)
                        @endphp
                        <tr class="hover:bg-black/[0.01] transition duration-150">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                                        @if($item->menu->image)
                                            <img src="{{ asset('storage/' . $item->menu->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#2c1f16] text-sm">{{ $item->menu->name }}</div>
                                        <div class="text-xs text-[#8a8a8a] font-medium mt-0.5">PRD-{{ str_pad($item->production_id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="py-4 px-6">
                                <span class="font-medium text-[#2c1f16] text-sm">{{ $item->production_date->format('d M Y') }}</span>
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm
                                        {{ $isExpired ? 'text-red-600' : ($isExpiringSoon ? 'text-amber-600' : 'text-[#2c1f16]') }}">
                                        {{ $item->expired_date->format('d M Y') }}
                                    </span>
                                    <span class="text-xs font-medium 
                                        {{ $isExpired ? 'text-red-500' : ($isExpiringSoon ? 'text-amber-500' : 'text-gray-400') }}">
                                        @if($isExpired)
                                            Sudah Basi!
                                        @elseif($daysRemaining === 0)
                                            Basi Besok Pagi
                                        @else
                                            Sisa {{ $daysRemaining }} Hari
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <td class="py-4 px-6 text-right">
                                <span class="text-xl font-black {{ $item->current_quantity > 0 ? 'text-[#2c1f16]' : 'text-gray-300' }}">{{ $item->current_quantity }}</span>
                                <span class="text-xs text-[#8a8a8a] font-medium ml-1">Porsi</span>
                                @if($item->current_quantity !== $item->initial_quantity)
                                    <div class="text-[10px] text-gray-400 font-medium">Dari {{ $item->initial_quantity }} awal</div>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-center">
                                @if($item->status === 'available' && !$isExpired && $item->current_quantity > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#EAF5EC] text-[#2E7D32]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]"></span> Ready
                                    </span>
                                @elseif($item->current_quantity == 0 || $item->status === 'empty')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                                        Habis
                                    </span>
                                @elseif($isExpired)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                                        Basi / Expired
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                    <p class="font-medium text-gray-500">Belum ada data Stok Jadi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection