@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-24 font-sans">
    
    <a href="{{ route('owner.productions.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#8a8a8a] hover:text-[#2c1f16] transition bg-white/50 px-4 py-2 rounded-xl w-fit">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Rencana
    </a>

    <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6 md:p-8 flex flex-col md:flex-row justify-between md:items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#fdfaf6] rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-black text-[#2c1f16] tracking-tight">PRD-{{ str_pad($production->id, 4, '0', STR_PAD_LEFT) }}</h1>
                @if($production->status === 'planned')
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-wider rounded-lg">Menunggu</span>
                @elseif($production->status === 'processing')
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider rounded-lg animate-pulse">Dimasak</span>
                @else
                    <span class="px-3 py-1 bg-[#EAF5EC] text-[#2E7D32] text-xs font-bold uppercase tracking-wider rounded-lg">Selesai</span>
                @endif
            </div>
            <p class="text-sm font-medium text-[#8a8a8a] flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4"></i> Target Produksi: <span class="text-[#2c1f16]">{{ \Carbon\Carbon::parse($production->plan_date)->format('l, d F Y') }}</span>
            </p>
        </div>

        <div class="flex gap-4 relative z-10">
            <div class="bg-[#fcfcfb] border border-black/5 rounded-2xl p-4 text-center min-w-[120px]">
                <p class="text-[10px] font-bold text-[#8a8a8a] uppercase tracking-wider mb-1">Target Porsi</p>
                <p class="text-2xl font-black text-[#2c1f16]">{{ $totalTargetCups }}</p>
            </div>
            <div class="bg-[#fcfcfb] border border-black/5 rounded-2xl p-4 text-center min-w-[120px]">
                <p class="text-[10px] font-bold text-[#8a8a8a] uppercase tracking-wider mb-1">Est. Modal (HPP)</p>
                <p class="text-xl font-black text-[#ef8d1d] mt-1">Rp {{ number_format($totalHpp, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-3xl border border-black/5 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-black/5 bg-black/[0.01] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#f5efe6] text-[#8b6f56] flex items-center justify-center">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    </div>
                    <h2 class="font-bold text-[#2c1f16]">Detail Target Masuk Etalase</h2>
                </div>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-black/5">
                            <th class="py-4 px-5 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider">Menu</th>
                            <th class="py-4 px-5 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider text-center">Target (Porsi)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @foreach($production->items as $item)
                        <tr class="hover:bg-black/[0.01] transition">
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                                        @if($item->menu->image)
                                            <img src="{{ asset('storage/' . $item->menu->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i data-lucide="coffee" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#2c1f16]">{{ $item->menu->name }}</p>
                                        <p class="text-xs text-[#8a8a8a]">{{ $item->menu->category }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="py-4 px-5 text-center">
                                <span class="font-bold text-[#2c1f16]">{{ $item->target_quantity }} Porsi</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($production->wastes && $production->wastes->isNotEmpty())
            <div class="bg-red-50 rounded-3xl border border-red-100 shadow-sm overflow-hidden mb-6">
                <div class="p-5 border-b border-red-100/50 bg-white/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-500 flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    </div>
                    <h2 class="font-bold text-red-700">Laporan Kerugian / Bahan Terbuang</h2>
                </div>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-red-100/50">
                            <th class="py-3 px-5 text-[10px] font-black text-red-400 uppercase tracking-wider">Bahan Mentah</th>
                            <th class="py-3 px-5 text-[10px] font-black text-red-400 uppercase tracking-wider text-right">Jumlah Terbuang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-100/50">
                        @foreach($production->wastes as $waste)
                        <tr>
                            <td class="py-3 px-5 text-sm font-bold text-red-900">{{ $waste->rawMaterial->name ?? 'Bahan Dihapus' }}</td>
                            <td class="py-3 px-5 text-sm font-black text-red-600 text-right">{{ $waste->quantity }} <span class="text-xs font-bold text-red-400">{{ $waste->rawMaterial->unit ?? '' }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-3xl border border-black/5 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-black/5 bg-black/[0.01] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                    </div>
                    <h2 class="font-bold text-[#2c1f16]">Log Catatan</h2>
                </div>
                
                <div class="p-5 space-y-5">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-2">Instruksi Owner:</span>
                        @if($production->notes)
                            <div class="text-sm text-[#5c4432] bg-[#f8f9fa] p-3 rounded-xl border border-gray-100">
                                {{ $production->notes }}
                            </div>
                        @else
                            <span class="text-sm text-gray-400 italic">- Tidak ada instruksi awal -</span>
                        @endif
                    </div>

                    <div class="w-full h-px bg-black/5"></div>

                    <div>
                        <span class="text-[10px] font-bold text-[#ef8d1d] uppercase tracking-wider block mb-2">Laporan Dapur:</span>
                        @if($production->execution_notes)
                            <div class="text-sm text-[#5c4432] bg-[#fdfaf6] p-3 rounded-xl border border-[#ece7e2] italic relative">
                                <div class="absolute -left-1.5 top-3 w-3 h-3 bg-[#fdfaf6] border-l border-t border-[#ece7e2] rotate-[-45deg]"></div>
                                "{{ $production->execution_notes }}"
                            </div>
                        @else
                            @if($production->status === 'completed' || $production->status === 'done')
                                <span class="text-sm text-gray-400 italic">- Aman, tidak ada laporan kerusakan -</span>
                            @else
                                <span class="text-sm text-gray-400 italic">- Dapur belum memproses tiket ini -</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">
                <h3 class="font-bold text-[#2c1f16] mb-4">Jejak Waktu</h3>
                
                <div class="relative pl-4 space-y-4 border-l-2 border-dashed border-gray-200">
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-3 h-3 bg-white border-2 border-gray-300 rounded-full"></div>
                        <p class="text-xs font-bold text-[#2c1f16]">Tiket Dibuat</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $production->created_at->format('d M Y • H:i:s') }}</p>
                    </div>

                    <div class="relative">
                        @if($production->status === 'completed' || $production->status === 'done')
                            <div class="absolute -left-[21px] top-1 w-3 h-3 bg-white border-2 border-green-500 rounded-full"></div>
                            <p class="text-xs font-bold text-green-600">Diselesaikan Dapur</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $production->updated_at->format('d M Y • H:i:s') }}</p>
                        @else
                            <div class="absolute -left-[21px] top-1 w-3 h-3 bg-white border-2 border-gray-200 rounded-full"></div>
                            <p class="text-xs font-bold text-gray-400">Belum Diselesaikan</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection