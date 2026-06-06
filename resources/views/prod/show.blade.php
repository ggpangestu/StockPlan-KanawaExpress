@extends('layouts.app')

@section('content')
<style>
    .main-content { padding: 40px; background: #f7f5f2; min-height: 100vh; border-radius: 24px; font-family: 'Inter', sans-serif;}
    .page-title { font-size: 36px; font-weight: 900; letter-spacing: -1px; color: #1d1d1d; }
    .card-custom { background: white; border: 1px solid #ece7e2; border-radius: 28px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,.03); }
    .soft-icon { width: 56px; height: 56px; border-radius: 18px; background: #f6f1eb; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #7b4a24; flex-shrink: 0;}
    .status-badge { display: inline-flex; align-items: center; padding: 7px 14px; border-radius: 999px; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;}
    .badge-planned { background: #fff3e6; color: #ef8d1d; }
    .badge-progress { background: #e9f2ff; color: #2570d8; }
    .finish-btn { height: 48px; border: none; border-radius: 16px; padding: 0 28px; background: #2faa39; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 10px 20px rgba(47,170,57,.18); transition: 0.2s;}
    .finish-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(47,170,57,.25);}
    .start-btn { height: 48px; border: none; border-radius: 16px; padding: 0 28px; background: #2570d8; color: white; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 10px 20px rgba(37, 112, 216, 0.18); transition: 0.2s;}
    .start-btn:hover { transform: translateY(-2px); }
    .section-title { font-size: 24px; font-weight: 800; color: #1f1f1f; margin-bottom: 0;}
    .info-icon { width: 48px; height: 48px; border-radius: 16px; display: flex; align-items: center; justify-content: center; background: #f6f1eb; color: #7a4b24; flex-shrink: 0; }
    .info-label { font-size: 12px; font-weight: 700; color: #8a8a8a; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .info-value { font-size: 18px; font-weight: 800; color: #1f1f1f; }
    .table-custom { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-custom thead th { font-size: 13px; font-weight: 700; color: #8d8d8d; text-transform: uppercase; border-bottom: 2px solid #f0ebe6; padding: 18px 20px; text-align: left;}
    .table-custom tbody td { padding: 20px; vertical-align: middle; border-bottom: 1px solid #f5f1ed; font-size: 15px; color: #2d2d2d; }
    .ingredient-icon { width: 42px; height: 42px; border-radius: 14px; background: #faf6f2; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #7b4a24;}
    .stock-badge { display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
    .safe { background: #edf8ea; color: #46a045; }
    .danger { background: #ffeaea; color: #e14b4b; }
</style>

<div class="main-content" x-data="{ isFinishModalOpen: false }">

    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-green-100 border border-green-200 text-green-700 px-5 py-4 font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('produksi.productions.index') }}" class="text-sm font-bold text-gray-500 hover:text-black flex items-center gap-2 transition w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Antrean
        </a>
    </div>

    <form id="finishForm" action="{{ route('produksi.productions.complete', $production) }}" method="POST">
        @csrf
        
        <div class="card-custom mb-6">
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="soft-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1Z"/><path d="m9 14 2 2 4-4"/></svg>
                    </div>
                    <div>
                        <h2 class="section-title">Produksi PRD-{{ str_pad($production->id, 4, '0', STR_PAD_LEFT) }}</h2>
                        <div class="mt-2">
                            @if($production->status === 'planned')
                                <span class="status-badge badge-planned">Menunggu Eksekusi</span>
                            @else
                                <span class="status-badge badge-progress">Work in Progress</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    @if($production->status === 'planned')
                        <button type="button" onclick="document.getElementById('startForm').submit()" class="start-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            Mulai Kerjakan
                        </button>
                    @elseif($production->status === 'processing')
                        <button type="button" @click="isFinishModalOpen = true" class="finish-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                            Selesaikan Produksi
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-custom mb-6">
            <div class="flex items-center gap-3 mb-8">
                <div class="soft-icon !w-12 !h-12 !rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                </div>
                <h3 class="section-title">Detail Produksi</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:divide-x-2 md:divide-dashed md:divide-[#eee8e3]">
                <div class="flex items-center gap-4 md:pr-6">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
                    </div>
                    <div>
                        <div class="info-label">Target Produksi</div>
                        <div class="info-value">{{ $totalCups }} <span class="text-sm font-medium text-gray-400">porsi</span></div>
                    </div>
                </div>

                <div class="flex items-center gap-4 md:px-6">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                    <div>
                        <div class="info-label">Tanggal Target</div>
                        <div class="info-value">{{ $production->plan_date->format('d M Y') }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 md:pl-6">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                    </div>
                    <div>
                        <div class="info-label">Est. Total Modal (HPP)</div>
                        <div class="info-value">Rp {{ number_format($totalHpp, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-custom mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="soft-icon !w-12 !h-12 !rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
                </div>
                <h3 class="section-title">Menu yang Harus Dibuat</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="table-custom min-w-[600px] w-full table-fixed">
                    <thead>
                        <tr>
                            <th class="text-left w-[70%]">Menu Product</th>
                            <th class="text-center w-[30%]">Target (Porsi)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($production->items as $item)
                        <tr>
                            <td class="py-4 align-middle pr-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                                        @if($item->menu->image)
                                            <img src="{{ asset('storage/' . $item->menu->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 text-lg truncate">{{ $item->menu->name }}</div>
                                        <div class="text-xs text-gray-500 font-medium truncate">Kategori: {{ $item->menu->category }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="py-4 align-middle text-center">
                                <span class="font-black text-xl text-[#2c1f16]">{{ $item->target_quantity }}</span>
                                <span class="text-xs text-gray-500 block mt-1">Wajib Jadi</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($production->status === 'processing')
        <div x-data="{ wastes: [] }" class="card-custom mb-6 border-red-100 bg-[#fffafa] shadow-none">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-4">
                    <div class="soft-icon !w-12 !h-12 !rounded-xl !bg-red-100 !text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                    </div>
                    <div>
                        <h3 class="section-title !text-red-600 !text-lg">Laporan Bahan Terbuang <span class="text-sm font-medium text-red-400">(Opsional)</span></h3>
                        <p class="text-xs text-red-400 mt-0.5">Jika ada bahan yang tumpah/gagal, catat di sini agar stok tetap akurat.</p>
                    </div>
                </div>
                <button type="button" @click="wastes.push({id: '', qty: ''})" class="h-10 px-4 bg-white border border-red-200 text-red-600 rounded-xl text-sm font-bold shadow-sm hover:bg-red-50 transition shrink-0">
                    + Tambah Catatan
                </button>
            </div>

            <div class="space-y-3 mt-6">
                <template x-for="(waste, index) in wastes" :key="index">
                    <div class="flex items-end gap-3 bg-white p-4 rounded-2xl border border-red-100 shadow-sm transition-all duration-200">
                        <div class="flex-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pilih Bahan Baku</label>
                            <select :name="'wasted_materials['+index+'][id]'" x-model="waste.id" required class="w-full h-11 mt-1.5 border-gray-200 rounded-xl text-sm focus:border-red-400 focus:ring-0 bg-gray-50 font-medium text-[#2c1f16]">
                                <option value="">-- Cari Bahan --</option>
                                @foreach($availableIngredients as $rm)
                                    <option value="{{ $rm->id }}">{{ $rm->name }} ({{ $rm->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="w-28 sm:w-32">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jml Tumpah</label>
                            <input type="number" step="0.01" :name="'wasted_materials['+index+'][qty]'" x-model="waste.qty" required class="w-full h-11 mt-1.5 border-gray-200 rounded-xl text-sm focus:border-red-400 focus:ring-0 bg-gray-50 font-bold text-center text-[#2c1f16]">
                        </div>

                        <button type="button" @click="wastes.splice(index, 1)" class="w-11 h-11 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>
                </template>
                
                <div x-show="wastes.length === 0" class="text-sm text-red-300 font-medium italic text-center py-6 border-2 border-dashed border-red-100 rounded-2xl bg-white/50">
                    Belum ada laporan bahan terbuang. Klik tombol + jika ada.
                </div>
            </div>
        </div>
        @endif

        <div class="card-custom mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="soft-icon !w-12 !h-12 !rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
                </div>
                <h3 class="section-title">Estimasi Kebutuhan Bahan</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="table-custom min-w-[600px]">
                    <thead>
                        <tr>
                            <th>Raw Material</th>
                            <th>Total Kebutuhan</th>
                            <th>Stok Fisik Tersedia</th>
                            <th>Status Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingredientsNeeded as $ing)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="ingredient-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#7b4a24]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                    </div>
                                    <strong class="text-gray-900">{{ $ing['name'] }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="font-bold text-gray-900">{{ number_format($ing['needed'], 1, ',', '.') }}</span> <span class="text-sm text-gray-500">{{ $ing['unit'] }}</span>
                            </td>
                            <td>
                                <span class="font-bold text-gray-900">{{ number_format($ing['stock'], 1, ',', '.') }}</span> <span class="text-sm text-gray-500">{{ $ing['unit'] }}</span>
                            </td>
                            <td>
                                @if($ing['stock'] >= $ing['needed'])
                                    <span class="stock-badge safe">Aman</span>
                                @else
                                    <span class="stock-badge danger">Kurang (Minus)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="isFinishModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center" style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="isFinishModalOpen = false" x-show="isFinishModalOpen" x-transition.opacity.duration.300ms></div>
            
            <div class="bg-white rounded-3xl p-6 max-w-sm w-full mx-4 relative z-10 shadow-2xl"
                 x-show="isFinishModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                
                <div class="w-16 h-16 rounded-full bg-[#EAF5EC] text-[#2E7D32] flex items-center justify-center mb-5 mx-auto border-[6px] border-[#EAF5EC]/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                </div>
                
                <h3 class="text-xl font-black text-center text-[#2c1f16] mb-2">Selesaikan Produksi?</h3>
                <p class="text-sm text-center text-[#5c4432] mb-7 leading-relaxed">Bahan baku akan dipotong secara otomatis, dan semua menu yang berhasil dibuat akan dipindahkan ke Gudang Stok Jadi.</p>
                <div class="mb-6 text-left">
                    <label class="block text-sm font-bold text-[#2c1f16] mb-2">Catatan Eksekusi (Opsional)</label>
                    <textarea 
                        name="execution_notes" 
                        rows="2" 
                        placeholder="Misal: Produksi lancar, tapi numpahin gula..." 
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm text-[#2c1f16] focus:ring-[#2faa39] focus:border-[#2faa39] transition placeholder-gray-400"
                    ></textarea>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" @click="isFinishModalOpen = false" class="flex-1 h-12 rounded-2xl border border-black/10 text-[#2c1f16] font-bold hover:bg-black/5 transition">Batal</button>
                    <button type="submit" class="flex-1 h-12 rounded-2xl bg-[#2faa39] text-white font-bold hover:bg-[#289631] shadow-lg shadow-green-600/30 transition">Ya, Selesaikan</button>
                </div>
            </div>
        </div>
    </form>

    <form id="startForm" action="{{ route('produksi.productions.start', $production) }}" method="POST" class="hidden">
        @csrf
        @method('PATCH')
    </form>
</div>
@endsection