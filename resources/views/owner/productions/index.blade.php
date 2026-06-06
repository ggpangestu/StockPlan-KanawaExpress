@extends('layouts.app')

@section('content')

<style>
    .custom-pagination nav {
        display: flex; justify-content: space-between; align-items: center;
    }
    .custom-pagination nav span[aria-current="page"] span {
        background-color: #2c1f16 !important; 
        color: white !important; 
        border-color: #2c1f16 !important;
    }
    .custom-pagination nav a {
        color: #5c4432 !important;
        transition: all 0.2s ease;
    }
    .custom-pagination nav a:hover {
        background-color: #f5efe6 !important;
        color: #2c1f16 !important;
    }
    .custom-pagination svg { width: 1.25rem; height: 1.25rem; }
</style>

<div x-data="productionManager()" class="relative min-h-screen">
    
    <div class="space-y-6 pb-24">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#2c1f16]">Production Plan</h1>
                <p class="text-[#5c4432] mt-1">Kelola dan jadwalkan tugas untuk tim produksi.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    
                    <div class="relative">
                        <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" 
                               class="h-11 pl-4 pr-3 rounded-2xl border border-black/10 bg-white text-sm text-[#2c1f16] shadow-sm focus:ring-[#ef8d1d] focus:border-[#ef8d1d] transition cursor-pointer">
                        @if(request('date'))
                            <a href="{{ route('owner.productions.index', ['filter' => request('filter')]) }}" class="absolute -top-2 -right-2 w-5 h-5 bg-red-100 text-red-500 rounded-full flex items-center justify-center hover:bg-red-200 transition" title="Hapus Filter Tanggal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </a>
                        @endif
                    </div>

                    <select name="filter" onchange="this.form.submit()" class="h-11 rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#2c1f16] shadow-sm focus:ring-[#ef8d1d] focus:border-[#ef8d1d]">
                        <option value="all" {{ request('filter') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="planned" {{ request('filter') === 'planned' ? 'selected' : '' }}>Planned (Menunggu)</option>
                        <option value="processing" {{ request('filter') === 'processing' ? 'selected' : '' }}>Processing (Dikerjakan)</option>
                        <option value="completed" {{ request('filter') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    </select>
                </form>

                <button type="button" @click="openCreatePanel()" class="inline-flex items-center px-5 h-11 rounded-2xl bg-[#2c1f16] text-white font-medium hover:opacity-90 transition shadow-sm">
                    + Buat Rencana
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-green-50/80 border border-green-200 text-green-700 px-5 py-4 font-medium backdrop-blur-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl bg-red-50/80 border border-red-200 text-red-700 px-5 py-4 font-medium backdrop-blur-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white border border-black/5 rounded-3xl overflow-hidden shadow-sm mt-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-black/[0.02] border-b border-black/5">
                            <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider">ID & Tanggal</th>
                            <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider">Target Menu</th>
                            <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider w-1/3">Catatan / Laporan Produksi</th>
                            <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider text-center">Status</th>
                            <th class="py-4 px-6 text-xs font-bold text-[#8a8a8a] uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @forelse($productions as $prod)
                            
                            <!-- 1. LOGIKA PENGECEKAN KE PALING ATAS BARIS -->
                            @php
                                $adaSelisih = false;
                                if($prod->status === 'completed' || $prod->status === 'done') {
                                    // Tiket merah jika ada tabel riwayat sampah/wasted
                                    $adaSelisih = $prod->wastes->isNotEmpty(); 
                                }
                            @endphp
                            
                            <!-- 2. HIGHLIGHT BARIS (JIKA ADA SELISIH, WARNA BACKGROUND BERUBAH) -->
                            <tr class="transition duration-150 {{ $adaSelisih ? 'bg-red-50/50 hover:bg-red-50' : 'hover:bg-black/[0.01]' }}">
                                
                                <td class="py-4 px-6 align-top">
                                    <div class="font-bold text-[#2c1f16]">PRD-{{ str_pad($prod->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-xs text-[#8a8a8a] font-medium mt-1">
                                        {{ \Carbon\Carbon::parse($prod->plan_date)->format('d M Y') }}
                                    </div>
                                </td>

                                <td class="py-4 px-6 align-top">
                                    <ul class="space-y-1">
                                        @foreach($prod->items as $item)
                                            <li class="text-sm font-medium text-[#5c4432]">
                                                <span class="font-bold text-[#2c1f16]">{{ $item->target_quantity }}x</span> {{ $item->menu->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <td class="py-4 px-6 align-top">
                                    <div class="space-y-3">
                                        
                                        @if($prod->notes)
                                            <div>
                                                <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider block mb-1">Instruksi Awal:</span>
                                                <div class="text-sm text-blue-800 bg-blue-50/50 p-2.5 rounded-xl border border-blue-100">
                                                    "{{ $prod->notes }}"
                                                </div>
                                            </div>
                                        @endif

                                        <!-- 3. WARNA CATATAN DAPUR SEKARANG DINAMIS -->
                                        @if($prod->execution_notes)
                                            <div>
                                                @if($adaSelisih)
                                                    <!-- Jika Bermasalah: MERAH -->
                                                    <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider block mb-1">Laporan Kendala:</span>
                                                    <div class="text-sm text-red-800 bg-red-100/50 p-2.5 rounded-xl border border-red-200 italic shadow-sm">
                                                        "{{ $prod->execution_notes }}"
                                                    </div>
                                                @else
                                                    <!-- Jika Mulus: HIJAU -->
                                                    <span class="text-[10px] font-bold text-[#2E7D32] uppercase tracking-wider block mb-1">Laporan Produksi:</span>
                                                    <div class="text-sm text-[#2E7D32] bg-[#EAF5EC]/50 p-2.5 rounded-xl border border-[#c3e0c7] italic">
                                                        "{{ $prod->execution_notes }}"
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if(!$prod->notes && !$prod->execution_notes)
                                            <span class="text-sm text-gray-400 italic">- Tidak ada catatan -</span>
                                        @endif

                                    </div>
                                </td>

                                <td class="py-4 px-6 align-middle text-center">
                                    @if($prod->status === 'planned')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#fff3e6] text-[#ef8d1d]">
                                            Menunggu
                                        </span>
                                    @elseif($prod->status === 'processing')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e9f2ff] text-[#2570d8] animate-pulse">
                                            <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Dimasak
                                        </span>
                                    @elseif($prod->status === 'completed' || $prod->status === 'done')
                                        
                                        @if($adaSelisih)
                                            <!-- PERUBAHAN DI SINI: Teks menjadi "Selesai (Selisih)" dengan ikon Segitiga Peringatan -->
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#fff0f0] border border-[#ffdada] text-[#e14b4b] shadow-sm" title="Produksi sudah selesai, tapi ada selisih target">
                                                <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Selesai (Ada Catatan)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#edf8ea] border border-[#c6eac3] text-[#2faa39]">
                                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Selesai
                                            </span>
                                        @endif

                                    @endif
                                </td>

                                <td class="py-4 px-6 align-middle text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2 flex-nowrap">
                                        
                                        <a href="{{ route('owner.productions.show', $prod) }}" 
                                        class="inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 transition" 
                                        title="Lihat Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>

                                        @if($prod->status === 'planned')
                                            <button type="button" @click="openEditPanel({{ $prod }}, '{{ \Carbon\Carbon::parse($prod->plan_date)->format('Y-m-d') }}')" 
                                                    class="inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition" title="Edit">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </button>
                                            
                                            <button type="button" @click="openDeleteModal('{{ route('owner.productions.destroy', $prod) }}')" 
                                                    class="inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        @else
                                            <div class="inline-flex items-center justify-center h-8 px-3 shrink-0 rounded-lg bg-gray-50 border border-gray-100">
                                                <i data-lucide="lock" class="w-3.5 h-3.5 text-gray-400 mr-1.5"></i>
                                                <span class="text-xs font-semibold text-gray-500 tracking-wide">Locked</span>
                                            </div>
                                        @endif
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400 font-medium">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="clipboard-list" class="w-12 h-12 text-[#5c4432]/30 mb-4"></i>
                                        <h3 class="text-lg font-bold text-[#2c1f16]">Belum ada Rencana Produksi</h3>
                                        @if(request('date'))
                                            <p class="text-sm mt-2 text-gray-400">Tidak ada jadwal untuk tanggal {{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 custom-pagination">
            {{ $productions->appends(request()->query())->links() }}
        </div>
    </div>

    <div x-show="isDeleteModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="closeDeleteModal()" x-show="isDeleteModalOpen" x-transition.opacity.duration.300ms></div>
        
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full mx-4 relative z-10 shadow-2xl"
             x-show="isDeleteModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4">
            
            <div class="w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center mb-5 mx-auto border-[6px] border-red-50/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
            </div>
            
            <h3 class="text-xl font-black text-center text-[#2c1f16] mb-2">Hapus Rencana?</h3>
            <p class="text-sm text-center text-[#5c4432] mb-7 leading-relaxed">Tindakan ini tidak dapat dibatalkan. Semua data target produksi pada tiket ini akan dihapus permanen.</p>
            
            <div class="flex items-center gap-3">
                <button type="button" @click="closeDeleteModal()" class="flex-1 h-12 rounded-2xl border border-black/10 text-[#2c1f16] font-bold hover:bg-black/5 transition">Batal</button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full h-12 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 shadow-lg shadow-red-600/30 transition">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>


    <div>
        <div x-show="isPanelOpen" 
             x-transition.opacity.duration.300ms
             @click="closePanel()"
             class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40" style="display: none;"></div>

        <div x-show="isPanelOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 z-50 w-full max-w-xl bg-[#fdfdfd] shadow-2xl border-l border-black/10 flex flex-col"
             style="display: none;">
            
            <div class="px-6 py-5 border-b border-black/5 bg-white flex justify-between items-center shrink-0">
                <div>
                    <h2 class="text-xl font-bold text-[#2c1f16]" x-text="editMode ? 'Edit Rencana Produksi' : 'Buat Rencana Baru'"></h2>
                    <p class="text-xs text-[#5c4432] mt-0.5" x-text="editMode ? 'Ubah jadwal atau jumlah porsi' : 'Pilih menu yang akan dibuat tim produksi'"></p>
                </div>
                <button @click="closePanel()" type="button" class="w-8 h-8 rounded-full bg-black/5 flex items-center justify-center hover:bg-black/10 transition">
                    <i data-lucide="x" class="w-4 h-4 text-[#2c1f16]"></i>
                </button>
            </div>

            <form :action="formAction" method="POST" class="flex-1 flex flex-col overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-[#5c4432] uppercase">Tanggal</label>
                            <input type="date" name="plan_date" x-model="formData.plan_date" required min="{{ date('Y-m-d') }}" class="w-full mt-1.5 rounded-xl border border-black/10 px-3 h-10 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-[#5c4432] uppercase">Catatan</label>
                            <input type="text" name="notes" x-model="formData.notes" placeholder="Opsional" class="w-full mt-1.5 rounded-xl border border-black/10 px-3 h-10 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-[#5c4432] uppercase mb-4 block">1. Klik Menu untuk Menambah</label>
                        
                        <template x-for="(menus, category) in groupedMenus" :key="category">
                            <div class="mb-5">
                                <div class="flex items-center gap-3 mb-3">
                                    <h4 class="text-[10px] font-black text-[#8b6f56] uppercase tracking-wider" x-text="category"></h4>
                                    <div class="h-px flex-1 bg-black/10"></div>
                                </div>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <template x-for="menu in menus" :key="menu.id">
                                        <div @click="addMenuToPlan(menu)" 
                                             class="relative p-2.5 rounded-2xl border border-black/10 bg-white cursor-pointer hover:border-[#8b6f56] hover:shadow-sm transition group flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-[#f5efe6] overflow-hidden shrink-0 flex items-center justify-center">
                                                <template x-if="menu.image">
                                                    <img :src="`/storage/${menu.image}`" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!menu.image">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#8b6f56]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
                                                </template>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-xs font-bold text-[#2c1f16] truncate" x-text="menu.name"></div>
                                            </div>
                                            <div class="absolute right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-white/80 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#2c1f16]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-[#5c4432] uppercase mb-3 block">2. Tentukan Jumlah (Porsi)</label>
                        
                        <div x-show="formData.items.length === 0" class="text-center p-6 border border-dashed border-black/10 rounded-2xl bg-black/[0.02]">
                            <p class="text-xs text-[#5c4432]">Belum ada menu yang dipilih. Klik kartu menu di atas.</p>
                        </div>

                        <div class="space-y-2">
                            <template x-for="(item, index) in formData.items" :key="index">
                                <div class="flex items-center justify-between p-3 rounded-2xl border border-black/10 bg-white shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#f5efe6] flex items-center justify-center shrink-0 overflow-hidden">
                                            <template x-if="item.image">
                                                <img :src="`/storage/${item.image}`" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!item.image">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#8b6f56]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
                                            </template>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-[#2c1f16]" x-text="item.name"></div>
                                            <input type="hidden" :name="`items[${index}][menu_id]`" :value="item.menu_id">
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center bg-[#f5efe6] rounded-lg border border-[#e6dcd0] overflow-hidden">
                                            <button type="button" @click="if(item.target_quantity > 1) { item.target_quantity--; calculateStock(); }" class="w-8 h-8 flex items-center justify-center text-[#5c4432] hover:bg-black/5 font-bold">-</button>
                                            <input type="number" :name="`items[${index}][target_quantity]`" x-model.number="item.target_quantity" @input="calculateStock()" min="1" class="w-10 h-8 p-0 text-center text-sm font-bold bg-transparent border-none focus:ring-0">
                                            <button type="button" @click="item.target_quantity++; calculateStock();" class="w-8 h-8 flex items-center justify-center text-[#5c4432] hover:bg-black/5 font-bold">+</button>
                                        </div>
                                        <button type="button" @click="removeMenuFromPlan(index)" class="text-red-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="Object.keys(missingStocks).length > 0" x-transition class="bg-red-50 rounded-2xl border border-red-200 p-4">
                        <div class="flex items-start gap-3 text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                            <div class="flex-1">
                                <h3 class="font-bold text-sm mb-1">Stok Bahan Baku Kurang</h3>
                                <ul class="space-y-1 mt-2">
                                    <template x-for="(data, rm_id) in missingStocks" :key="rm_id">
                                        <li class="text-xs bg-white/60 p-2 rounded-lg border border-red-100 flex justify-between items-center">
                                            <span class="font-medium" x-text="data.name"></span>
                                            <span class="text-red-600 font-bold" x-text="`Kurang ${Math.abs(data.available - data.required).toFixed(2)} ${data.unit}`"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="p-5 border-t border-black/5 bg-white flex items-center justify-end gap-3 shrink-0">
                    <button type="button" @click="closePanel()" class="h-10 px-5 rounded-xl border border-black/10 text-[#2c1f16] text-sm font-medium hover:bg-black/5 transition">
                        Batal
                    </button>

                    <button type="submit" 
                            :disabled="formData.items.length === 0 || Object.keys(missingStocks).length > 0"
                            class="h-10 px-6 rounded-xl text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-300 disabled:shadow-none"
                            :class="Object.keys(missingStocks).length > 0 ? 'bg-red-600 text-white' : 'bg-[#2c1f16] hover:opacity-90 text-white'">
                        <span x-text="editMode ? 'Simpan Perubahan' : 'Kirim ke Dapur'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function productionManager() {
        return {
            menusData: @json($menus),
            rawMaterials: @json($rawMaterials),
            productionReservations: @json((object) ($productionReservations ?? [])),
            
            // State untuk Panel Edit/Create
            isPanelOpen: false,
            editMode: false,
            currentEditId: null,
            formAction: '{{ route('owner.productions.store') }}',
            
            // State untuk Delete Modal
            isDeleteModalOpen: false,
            deleteUrl: '',
            
            formData: {
                plan_date: '{{ date('Y-m-d') }}',
                notes: '',
                items: []
            },
            
            missingStocks: {},

            get groupedMenus() {
                let groups = {};
                this.menusData.forEach(menu => {
                    let cat = menu.category || 'Lainnya';
                    if (!groups[cat]) {
                        groups[cat] = [];
                    }
                    groups[cat].push(menu);
                });
                return groups;
            },

            // --- Fungsi Delete Modal ---
            openDeleteModal(url) {
                this.deleteUrl = url;
                this.isDeleteModalOpen = true;
            },
            closeDeleteModal() {
                this.isDeleteModalOpen = false;
                this.deleteUrl = '';
            },

            // --- Fungsi Panel Create/Edit ---
            openCreatePanel() {
                this.editMode = false;
                this.currentEditId = null;
                this.formAction = '{{ route('owner.productions.store') }}';
                this.formData = { plan_date: '{{ date('Y-m-d') }}', notes: '', items: [] };
                this.missingStocks = {};
                this.isPanelOpen = true;
            },

            // PERBAIKAN: Fungsi ini sekarang menerima format tanggal murni dari server
            openEditPanel(production, formattedDate) {
                this.editMode = true;
                this.currentEditId = production.id;
                this.formAction = `/owner/productions/${production.id}`;
                
                let mappedItems = production.items.map(i => {
                    return {
                        menu_id: i.menu_id,
                        name: i.menu.name,
                        image: i.menu.image,
                        target_quantity: i.target_quantity
                    };
                });

                this.formData = { 
                    plan_date: formattedDate, // Menggunakan tanggal PHP yang akurat
                    notes: production.notes || '', 
                    items: mappedItems 
                };
                
                this.calculateStock();
                this.isPanelOpen = true;
            },

            closePanel() {
                this.isPanelOpen = false;
            },

            addMenuToPlan(menu) {
                let existing = this.formData.items.find(i => i.menu_id === menu.id);
                if (existing) {
                    existing.target_quantity++;
                } else {
                    this.formData.items.push({
                        menu_id: menu.id,
                        name: menu.name,
                        image: menu.image,
                        target_quantity: 1
                    });
                }
                this.calculateStock();
            },

            removeMenuFromPlan(index) {
                this.formData.items.splice(index, 1);
                this.calculateStock();
            },

            calculateStock() {
                let requiredMaterials = {};
                this.missingStocks = {};

                this.formData.items.forEach(item => {
                    if(item.target_quantity <= 0) return;
                    let menu = this.menusData.find(m => m.id == item.menu_id);
                    if(!menu) return;

                    menu.ingredients.forEach(ing => {
                        let totalNeeded = ing.pivot.quantity * item.target_quantity;
                        requiredMaterials[ing.id] = (requiredMaterials[ing.id] || 0) + totalNeeded;
                    });
                });

                for (const [rm_id, requiredQty] of Object.entries(requiredMaterials)) {
                    let stockInfo = this.rawMaterials[rm_id];
                    
                    if (stockInfo) {
                        let refundBooking = 0;
                        if (this.currentEditId && this.productionReservations[this.currentEditId] && this.productionReservations[this.currentEditId][rm_id]) {
                            refundBooking = this.productionReservations[this.currentEditId][rm_id];
                        }
                        
                        let effectiveAvailable = stockInfo.available_stock + refundBooking;

                        if (requiredQty > effectiveAvailable) {
                            this.missingStocks[rm_id] = {
                                name: stockInfo.name,
                                required: requiredQty.toFixed(2),
                                available: effectiveAvailable.toFixed(2),
                                unit: stockInfo.unit
                            };
                        }
                    }
                }
            }
        }
    }
</script>
@endsection