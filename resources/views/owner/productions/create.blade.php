{{-- @extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6">
        <h1 class="text-3xl font-bold text-[#2c1f16]">Buat Rencana Produksi</h1>
        <p class="text-[#5c4432] mt-1">Sistem otomatis mengecek kesiapan bahan baku di gudang.</p>
    </div>

    <div x-data="productionPlanner()" class="space-y-6">
        
        <form action="{{ route('owner.productions.store') }}" method="POST" id="productionForm">
            @csrf

            <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-[#5c4432]">Tanggal Target Produksi</label>
                        <input type="date" name="plan_date" required min="{{ date('Y-m-d') }}" class="w-full mt-2 rounded-2xl border border-black/10 px-4 h-11">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#5c4432]">Catatan untuk Dapur (Opsional)</label>
                        <input type="text" name="notes" placeholder="Contoh: Utamakan Matcha Latte selesai jam 10 pagi" class="w-full mt-2 rounded-2xl border border-black/10 px-4 h-11">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-black/5 shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-[#2c1f16]">Menu yang Ingin Diproduksi</h2>
                    <button type="button" @click="addItem()" class="inline-flex items-center h-9 px-4 rounded-xl bg-black/[0.04] text-[#2c1f16] text-sm font-medium hover:bg-black/[0.08] transition">
                        + Tambah Menu
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="item.id">
                        <div class="flex items-end gap-4 p-4 rounded-2xl border border-black/10 bg-black/[0.01]">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-[#5c4432]">Pilih Menu</label>
                                <select :name="`items[${index}][menu_id]`" x-model="item.menu_id" @change="calculateStock()" class="w-full mt-2 rounded-xl border border-black/10 px-4 h-11" required>
                                    <option value="">Pilih Menu...</option>
                                    <template x-for="menu in menusData" :key="menu.id">
                                        <option :value="menu.id" x-text="menu.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="w-1/3">
                                <label class="text-sm font-medium text-[#5c4432]">Target Porsi/Botol</label>
                                <input type="number" :name="`items[${index}][target_quantity]`" x-model.number="item.qty" @input="calculateStock()" min="1" class="w-full mt-2 rounded-xl border border-black/10 px-4 h-11" required>
                            </div>
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="h-11 w-11 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition shrink-0">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="Object.keys(missingStocks).length > 0" x-transition class="bg-red-50 rounded-3xl border border-red-200 p-6 mb-6">
                <div class="flex items-start gap-3 text-red-700">
                    <i data-lucide="alert-triangle" class="w-6 h-6 shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="font-bold text-lg mb-1">Peringatan: Stok Bahan Baku Tidak Cukup!</h3>
                        <p class="text-sm mb-4">Total rencana produksi Anda melebihi kapasitas bahan baku di gudang saat ini. Rencana ini tetap bisa disimpan, tetapi dapur mungkin tidak bisa menyelesaikannya tanpa restock.</p>
                        
                        <ul class="space-y-2">
                            <template x-for="(data, rm_id) in missingStocks" :key="rm_id">
                                <li class="text-sm bg-white/60 p-2.5 rounded-xl border border-red-100 flex justify-between items-center">
                                    <span class="font-semibold" x-text="data.name"></span>
                                    <div class="text-right">
                                        <span class="text-red-600 font-bold" x-text="`Butuh: ${data.required} ${data.unit}`"></span>
                                        <span class="text-gray-500 ml-2" x-text="`(Stok: ${data.available} ${data.unit})`"></span>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('owner.productions.index') }}" class="inline-flex items-center justify-center h-11 px-5 rounded-2xl border border-black/10 text-[#2c1f16] font-medium hover:bg-black/[0.03] transition">Batal</a>
                
                <button type="submit" 
                        class="inline-flex items-center justify-center h-11 px-6 rounded-2xl font-medium transition"
                        :class="Object.keys(missingStocks).length > 0 ? 'bg-red-600 hover:bg-red-700 text-white shadow-[0_0_15px_rgba(220,38,38,0.5)]' : 'bg-[#2c1f16] hover:opacity-90 text-white'">
                    <span x-text="Object.keys(missingStocks).length > 0 ? 'Tetap Simpan Rencana' : 'Kirim ke Dapur'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function productionPlanner() {
        return {
            // Injeksi data dari backend via JSON
            menusData: @json($menus),
            rawMaterials: @json($rawMaterials),
            
            items: [ { id: Date.now(), menu_id: '', qty: 1 } ],
            missingStocks: {}, // Akan diisi material yang kurang

            addItem() {
                this.items.push({ id: Date.now(), menu_id: '', qty: 1 });
                this.calculateStock();
            },
            
            removeItem(index) {
                this.items.splice(index, 1);
                this.calculateStock();
            },

            calculateStock() {
                // 1. Reset kalkulasi kebutuhan material
                let requiredMaterials = {};
                this.missingStocks = {};

                // 2. Loop setiap menu yang ingin diproduksi
                this.items.forEach(item => {
                    if(!item.menu_id || item.qty <= 0) return;
                    
                    // Cari data resep menu ini
                    let menu = this.menusData.find(m => m.id == item.menu_id);
                    if(!menu) return;

                    // 3. Kalikan resep (ingredients) dengan target quantity
                    menu.ingredients.forEach(ing => {
                        let totalNeeded = ing.pivot.quantity * item.qty;
                        
                        if(requiredMaterials[ing.id]) {
                            requiredMaterials[ing.id] += totalNeeded;
                        } else {
                            requiredMaterials[ing.id] = totalNeeded;
                        }
                    });
                });

                // 4. Bandingkan akumulasi kebutuhan dengan gudang (Raw Materials)
                for (const [rm_id, requiredQty] of Object.entries(requiredMaterials)) {
                    let stockInfo = this.rawMaterials[rm_id];
                    if (stockInfo && requiredQty > stockInfo.stock) {
                        // Jika kurang, masukkan ke list merah
                        this.missingStocks[rm_id] = {
                            name: stockInfo.name,
                            required: requiredQty.toFixed(2),
                            available: stockInfo.stock.toFixed(2),
                            unit: stockInfo.unit
                        };
                    }
                }
            }
        }
    }
</script>
@endsection --}}