@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F9F7F2] font-sans text-gray-900 rounded-3xl overflow-hidden">
    <main class="flex-1 p-8">

        {{-- Info bar --}}
        <div class="flex items-center gap-2 text-gray-500 text-xs mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9a3412" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Sistem Gudang & Produksi Kanawa Express - <span class="font-bold text-green-600">Terhubung</span></span>
        </div>

        {{-- Separator --}}
        <hr class="border-gray-200 mb-8">

        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-green-100 border border-green-200 text-green-700 px-5 py-4 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div class="flex items-center gap-4">
                <h2 class="text-4xl font-black">Production Queue</h2>
            </div>
        </div>

        <div class="space-y-6">
            @forelse($productions as $prod)
                <a href="{{ route('produksi.productions.show', $prod) }}" class="block group">
                    <div class="bg-white border border-gray-200 rounded-3xl p-8 md:p-10 flex flex-col md:flex-row md:items-center justify-between shadow-sm transition hover:border-black hover:shadow-md gap-4">
                        
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <h3 class="text-2xl md:text-3xl font-black group-hover:text-blue-600 transition">
                                    PRD-{{ str_pad($prod->id, 4, '0', STR_PAD_LEFT) }}
                                </h3>
                                <span class="text-sm font-bold text-gray-400">{{ $prod->plan_date->format('d M Y') }}</span>
                            </div>
                            
                            @if($prod->status === 'planned')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase">
                                    Menunggu Eksekusi
                                </span>
                            @elseif($prod->status === 'processing')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-600 border border-blue-200 uppercase animate-pulse">
                                    WORK IN PROGRESS
                                </span>
                            @endif

                            @if($prod->notes)
                                <p class="text-xs text-gray-500 mt-3 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                    {{ $prod->notes }}
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="text-right hidden md:block">
                                <div class="text-sm text-gray-500">Total Item</div>
                                <div class="text-xl font-bold text-gray-900">{{ $prod->items->sum('target_quantity') }} <span class="text-sm font-medium">Porsi</span></div>
                            </div>
                            <div class="text-gray-300 group-hover:text-black transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            </div>
                        </div>

                    </div>
                </a>
            @empty
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium">Hore! Tidak ada antrean produksi untuk dapur hari ini.</p>
                </div>
            @endforelse
        </div>

    </main>
</div>
@endsection