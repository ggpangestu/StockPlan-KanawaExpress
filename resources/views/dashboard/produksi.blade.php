@extends('layouts.app')

@section('content')
<style>
    .card-custom { background: white; border: 1px solid #ece7e2; border-radius: 28px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,.03); }
    .icon-box { width: 48px; height: 48px; border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
</style>

<div class="space-y-8 font-sans pb-10">

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-[#2c1f16] tracking-tight">
                Halo, Tim Dapur! 👋
            </h1>
            <p class="text-[#5c4432] mt-2 font-medium">
                Pantau antrean, masak pesanan, dan pastikan kualitas produk Kanawa Express.
            </p>
        </div>
        <a href="{{ route('produksi.productions.index') }}" class="h-12 px-6 bg-[#2c1f16] text-white rounded-2xl font-bold flex items-center gap-2 hover:bg-[#1a120d] transition shadow-lg shadow-black/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            Mulai Eksekusi
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="card-custom relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-4">
                    <div class="icon-box bg-amber-100 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Antrean Baru</h3>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-[#2c1f16]">{{ $tiketMenunggu ?? 0 }}</span>
                    <span class="text-sm font-bold text-amber-600">Tiket Aktif</span>
                </div>
            </div>
        </div>

        <div class="card-custom relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-4">
                    <div class="icon-box bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Sedang Dimasak</h3>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-[#2c1f16]">{{ $tiketDiproses ?? 0 }}</span>
                    <span class="text-sm font-bold text-blue-600">Tiket</span>
                </div>
            </div>
        </div>

        <div class="card-custom relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full transition-transform group-hover:scale-150"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-4">
                    <div class="icon-box bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Porsi Aktif</h3>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-[#2c1f16]">{{ $totalPorsiAktif ?? 0 }}</span>
                    <span class="text-sm font-bold text-green-600">Cup / Porsi</span>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="space-y-6">
            
            <div class="card-custom bg-[#fffafa] border border-red-100">
                <div class="flex items-center gap-3 mb-5">
                    <div class="icon-box !w-10 !h-10 bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#2c1f16]">Produksi Prioritas</h2>
                </div>

                @if($produksiPrioritas)
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-black text-lg text-[#2c1f16]">PRD-{{ str_pad($produksiPrioritas->id, 4, '0', STR_PAD_LEFT) }}</span>
                            @if($produksiPrioritas->status === 'planned')
                                <span class="bg-amber-100 text-amber-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase">Menunggu</span>
                            @else
                                <span class="bg-blue-100 text-blue-600 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase animate-pulse">Dimasak</span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            Tgl Target: <span class="font-bold text-gray-700">{{ $produksiPrioritas->plan_date->format('d M Y') }}</span>
                        </div>
                        
                        <div class="space-y-2 border-t border-dashed border-gray-200 pt-3">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Menu yang Dibuat:</p>
                            @foreach($produksiPrioritas->items as $item)
                                <div class="flex justify-between items-center text-sm font-medium">
                                    <span class="text-[#2c1f16]">{{ $item->menu->name }}</span>
                                    <span class="text-gray-900 bg-gray-100 px-2 py-0.5 rounded-lg">{{ $item->target_quantity }} porsi</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('produksi.productions.show', $produksiPrioritas) }}" class="block w-full text-center text-sm font-bold text-blue-600 bg-blue-50 py-3 rounded-xl hover:bg-blue-600 hover:text-white transition">Lihat Detail & Mulai Kerjakan</a>
                @else
                    <div class="text-center py-6">
                        <span class="text-gray-400 font-medium">Bagus! Tidak ada tiket antrean saat ini.</span>
                    </div>
                @endif
            </div>

            <div class="card-custom border border-red-50 bg-white shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="icon-box !w-10 !h-10 bg-orange-50 text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#2c1f16]">Stok Kritis</h2>
                </div>

                @if(isset($bahanMenipis) && $bahanMenipis->count() > 0)
                    <ul class="space-y-3">
                        @foreach($bahanMenipis as $bahan)
                        <li class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="font-bold text-[#2c1f16]">{{ $bahan->name }}</span>
                            <span class="text-sm font-black text-red-600">
                                Sisa: {{ number_format($bahan->opened_stock + ($bahan->sealed_stock * $bahan->conversion_value), 1) }} {{ $bahan->unit }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4">
                        <span class="text-green-600 font-bold text-sm">Semua bahan baku dalam batas aman.</span>
                    </div>
                @endif
            </div>

        </div>

        <div class="card-custom">
            <div class="flex items-center gap-3 mb-6">
                <div class="icon-box !w-10 !h-10 bg-gray-100 text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </div>
                <h2 class="text-lg font-bold text-[#2c1f16]">Riwayat Produksi Terakhir</h2>
            </div>
            
            <div class="space-y-4">
                @forelse($riwayatProduksi as $history)
                    <a href="{{ route('produksi.productions.show', $history) }}" class="block group">
                        <div class="flex items-center justify-between p-4 rounded-2xl border border-gray-100 hover:border-[#2c1f16] hover:shadow-md transition bg-gray-50 group-hover:bg-white">
                            <div>
                                <h4 class="font-black text-[#2c1f16] group-hover:text-blue-600 transition">PRD-{{ str_pad($history->id, 4, '0', STR_PAD_LEFT) }}</h4>
                                <p class="text-xs text-gray-500 mt-1 font-medium">{{ $history->updated_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <span class="bg-green-100 text-green-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase inline-block mb-1">
                                    Selesai
                                </span>
                                <p class="text-xs font-bold text-gray-500">{{ $history->items->sum('target_quantity') }} Porsi</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <p class="text-gray-400 font-medium text-sm">Belum ada riwayat produksi yang selesai.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection