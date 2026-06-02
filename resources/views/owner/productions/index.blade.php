@extends('layouts.app')

@section('content')
<!-- BUNGKUS UTAMA ALPINE.JS UNTUK STATE MANAGEMENT -->
<div x-data="productionManager()" class="relative min-h-screen">
    
    <div class="space-y-6 pb-24">
        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#2c1f16]">Production Plan</h1>
                <p class="text-[#5c4432] mt-1">Kelola dan jadwalkan tugas untuk tim dapur.</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET">
                    <select name="filter" onchange="this.form.submit()" class="h-11 rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#2c1f16] shadow-sm">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="planned" {{ $filter === 'planned' ? 'selected' : '' }}>Planned (Menunggu)</option>
                        <option value="processing" {{ $filter === 'processing' ? 'selected' : '' }}>Processing (Dikerjakan)</option>
                        <option value="completed" {{ $filter === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
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

        <!-- TICKETS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($productions as $prod)
                <div class="bg-white rounded-3xl border border-black/5 shadow-sm overflow-hidden flex flex-col relative group">
                    
                    <!-- Ticket Header -->
                    <div class="bg-[#f5efe6] p-5 border-b-2 border-dashed border-[#e6dcd0] relative">
                        <div class="absolute -left-3 -bottom-3 w-6 h-6 bg-white rounded-full border border-black/5 z-10 hidden md:block"></div>
                        <div class="absolute -right-3 -bottom-3 w-6 h-6 bg-white rounded-full border border-black/5 z-10 hidden md:block"></div>

                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-[#8b6f56] uppercase tracking-wider">PRD-{{ str_pad($prod->id, 4, '0', STR_PAD_LEFT) }}</span>
                            
                            @if($prod->status == 'planned')
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold uppercase rounded-lg">Planned</span>
                            @elseif($prod->status == 'processing')
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-lg animate-pulse">Processing</span>
                            @elseif($prod->status == 'completed')
                                <span class="px-2.5 py-1 bg-[#EAF5EC] text-[#2E7D32] text-[10px] font-bold uppercase rounded-lg">Completed</span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-black text-[#2c1f16]">{{ $prod->plan_date->format('l, d M Y') }}</h3>
                        @if($prod->notes)
                            <p class="text-xs text-[#5c4432] mt-2 italic flex gap-1.5"><i data-lucide="info" class="w-3.5 h-3.5"></i> {{ $prod->notes }}</p>
                        @endif
                    </div>

                    <!-- Ticket Body -->
                    <div class="p-5 flex-1 bg-white">
                        <ul class="space-y-3">
                            @foreach($prod->items as $item)
                                <li class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-black/5 flex items-center justify-center text-[#5c4432] shrink-0 overflow-hidden">
                                            @if($item->menu->image)
                                                <img src="{{ asset('storage/' . $item->menu->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <i data-lucide="coffee" class="w-4 h-4"></i>
                                            @endif
                                        </div>
                                        <span class="font-medium text-[#2c1f16] text-sm">{{ $item->menu->name }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-black text-[#2c1f16]">{{ $item->target_quantity }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- ACTION FOOTER UNTUK EDIT & DELETE (Khusus Planned) -->
                    @if($prod->status === 'planned')
                        <div class="px-5 py-3 border-t border-black/5 bg-black/[0.015] flex justify-end gap-4">
                            <!-- PERBAIKAN 1: MENGIRIM TANGGAL YANG SUDAH DIFOORMAT PHP -->
                            <button type="button" @click="openEditPanel({{ $prod }}, '{{ $prod->plan_date->format('Y-m-d') }}')" class="text-xs font-bold text-[#8b6f56] hover:text-[#2c1f16] flex items-center gap-1.5 transition">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                            </button>
                            <div class="w-px h-4 bg-black/10"></div>
                            
                            <!-- PERBAIKAN 2: MEMICU CUSTOM DELETE MODAL -->
                            <button type="button" @click="openDeleteModal('{{ route('owner.productions.destroy', $prod) }}')" class="text-xs font-bold text-red-500 hover:text-red-700 flex items-center gap-1.5 transition">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                            </button>
                        </div>
                    @endif

                </div>
            @empty
                <div class="col-span-full py-24 flex flex-col items-center justify-center border-2 border-dashed border-black/10 rounded-3xl bg-white/50">
                    <i data-lucide="clipboard-list" class="w-12 h-12 text-[#5c4432]/30 mb-4"></i>
                    <h3 class="text-lg font-bold text-[#2c1f16]">Belum ada Rencana Produksi</h3>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ================= MODAL HAPUS CUSTOM (UI MODERN) ================= -->
    <div x-show="isDeleteModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center" style="display: none;">
        <!-- Backdrop Gelap -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="closeDeleteModal()" x-show="isDeleteModalOpen" x-transition.opacity.duration.300ms></div>
        
        <!-- Kotak Modal -->
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


    <!-- ================= SLIDE-IN PANEL (OFF-CANVAS) ================= -->
    <div>
        <!-- Backdrop Gelap -->
        <div x-show="isPanelOpen" 
             x-transition.opacity.duration.300ms
             @click="closePanel()"
             class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40" style="display: none;"></div>

        <!-- Panel Kanan -->
        <div x-show="isPanelOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 z-50 w-full max-w-xl bg-[#fdfdfd] shadow-2xl border-l border-black/10 flex flex-col"
             style="display: none;">
            
            <!-- HEADER PANEL -->
            <div class="px-6 py-5 border-b border-black/5 bg-white flex justify-between items-center shrink-0">
                <div>
                    <h2 class="text-xl font-bold text-[#2c1f16]" x-text="editMode ? 'Edit Rencana Produksi' : 'Buat Rencana Baru'"></h2>
                    <p class="text-xs text-[#5c4432] mt-0.5" x-text="editMode ? 'Ubah jadwal atau jumlah porsi' : 'Pilih menu yang akan dibuat tim dapur'"></p>
                </div>
                <button @click="closePanel()" type="button" class="w-8 h-8 rounded-full bg-black/5 flex items-center justify-center hover:bg-black/10 transition">
                    <i data-lucide="x" class="w-4 h-4 text-[#2c1f16]"></i>
                </button>
            </div>

            <!-- ISI FORM -->
            <form :action="formAction" method="POST" class="flex-1 flex flex-col overflow-hidden">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    
                    <!-- TANGGAL & CATATAN -->
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

                    <!-- PEMILIHAN MENU (GROUP BY CATEGORY) -->
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
                                                    <!-- Raw SVG biar gak hilang disapu Lucide -->
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

                    <!-- MENU YANG DIPILIH DENGAN GAMBAR -->
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

                    <!-- SMART WARNING BOX -->
                    <div x-show="Object.keys(missingStocks).length > 0" x-transition class="bg-red-50 rounded-2xl border border-red-200 p-4">
                        <div class="flex items-start gap-3 text-red-700">
                            <!-- Raw SVG Warning -->
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

                <!-- PANEL FOOTER (ACTION BUTTONS) -->
                <div class="p-5 border-t border-black/5 bg-white flex items-center justify-end gap-3 shrink-0">
                    <button type="button" @click="closePanel()" class="h-10 px-5 rounded-xl border border-black/10 text-[#2c1f16] text-sm font-medium hover:bg-black/5 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            :disabled="formData.items.length === 0"
                            class="h-10 px-6 rounded-xl text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="Object.keys(missingStocks).length > 0 ? 'bg-red-600 hover:bg-red-700 text-white shadow-lg shadow-red-600/30' : 'bg-[#2c1f16] hover:opacity-90 text-white'">
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