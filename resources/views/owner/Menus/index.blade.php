@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[#2c1f16]">Menu Product</h1>
            <p class="text-[#5c4432] mt-1">Kelola daftar menu, estimasi HPP, dan resep Kanawa Express.</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET">
                <select name="filter" onchange="this.form.submit()" class="h-11 rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#2c1f16] shadow-sm cursor-pointer hover:bg-black/[0.02]">
                    <option value="active" {{ $filter === 'active' ? 'selected' : '' }}>Active Menus</option>
                    <option value="inactive" {{ $filter === 'inactive' ? 'selected' : '' }}>Archived Menus</option>
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Menus</option>
                </select>
            </form>
            <a href="{{ route('owner.menus.create') }}" class="inline-flex items-center px-5 h-11 rounded-2xl bg-[#2c1f16] text-white font-medium hover:opacity-90 transition shadow-sm">
                + Add Menu
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl bg-green-50/80 border border-green-200 text-green-700 px-5 py-4 font-medium backdrop-blur-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" x-data="{ visibleCount: {{ $menus->count() }} }">
        
        @forelse($menus as $menu)
            <div 
                x-data="{ 
                    active: {{ $menu->is_active ? 'true' : 'false' }}, 
                    visible: true,
                    async toggle(id) {
                        let previous = this.active;
                        this.active = !this.active;
                        await fetch(`/owner/menus/${id}/toggle-active`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json',
                            }
                        });
                        if ('{{ $filter }}' === 'active' && !this.active) { visibleCount--; this.visible = false; }
                        if ('{{ $filter }}' === 'inactive' && this.active) { visibleCount--; this.visible = false; }
                    }
                }" 
                x-show="visible" 
                x-transition.opacity.duration.300ms
                class="bg-white rounded-3xl border border-black/5 shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col relative group">
                
                <div class="absolute top-4 left-4 z-10">
                    @if($menu->is_ready)
                        <span class="px-3 py-1.5 bg-[#EAF5EC]/90 backdrop-blur border border-[#2E7D32]/20 text-[#2E7D32] text-[10px] font-extrabold uppercase tracking-widest rounded-xl shadow-sm flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]"></span> Ready to Brew
                        </span>
                    @else
                        <span class="px-3 py-1.5 bg-red-50/90 backdrop-blur border border-red-500/20 text-red-600 text-[10px] font-extrabold uppercase tracking-widest rounded-xl shadow-sm flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Low Ingredient
                        </span>
                    @endif
                </div>

                <div class="w-full aspect-[4/3] bg-[#f5efe6] relative overflow-hidden group-hover:bg-[#e6dcd0] transition-colors">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[#8b6f56]/30">
                            <i data-lucide="coffee" class="w-16 h-16"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>

                <div class="p-6 flex-1 flex flex-col">
                    <div class="text-xs font-bold text-[#8b6f56] uppercase tracking-wider mb-1.5">{{ $menu->category }}</div>
                    <h3 class="text-lg font-bold text-[#2c1f16] leading-tight mb-2">{{ $menu->name }}</h3>
                    <div class="text-2xl font-black text-[#5c4432] mb-5 tracking-tight">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </div>

                    <div class="mt-auto bg-[#f5efe6] rounded-2xl p-4 border border-[#e6dcd0]/50 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-[#5c4432] font-semibold flex items-center gap-1.5">
                                <i data-lucide="calculator" class="w-3.5 h-3.5"></i> Est. Cost (HPP)
                            </span>
                            <span class="text-sm font-bold text-[#2c1f16]">Rp {{ number_format($menu->hpp, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="h-px w-full bg-[#e6dcd0]"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-xs text-[#5c4432] font-semibold flex items-center gap-1.5">
                                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> Profit Margin
                            </span>
                            
                            @if($menu->margin_percentage >= 50)
                                <span class="text-xs font-bold text-[#2E7D32] bg-[#EAF5EC] px-2.5 py-1 rounded-lg">
                                    {{ number_format($menu->margin_percentage, 1) }}%
                                </span>
                            @elseif($menu->margin_percentage >= 30)
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200/50">
                                    {{ number_format($menu->margin_percentage, 1) }}%
                                </span>
                            @else
                                <span class="text-xs font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-lg border border-red-200/50">
                                    {{ number_format($menu->margin_percentage, 1) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-black/5 flex items-center justify-between bg-black/[0.015]">
                    <div class="flex items-center gap-2">
                        <button type="button" @click.stop="toggle({{ $menu->id }})" class="relative inline-flex h-7 w-12 items-center rounded-full transition duration-300 focus:outline-none" :class="active ? 'bg-[#2c1f16]' : 'bg-black/15'">
                            <span class="inline-block h-5 w-5 rounded-full bg-white transition duration-300 transform shadow-sm" :class="active ? 'translate-x-6' : 'translate-x-1'"></span>
                        </button>
                        <span class="text-xs font-bold text-[#5c4432]" x-text="active ? 'Active' : 'Archived'"></span>
                    </div>

                    <a href="{{ route('owner.menus.edit', $menu) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white border border-black/10 text-[#2c1f16] hover:bg-[#f5efe6] transition-colors shadow-sm">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
            
        @empty
            <div class="col-span-full py-24 flex flex-col items-center justify-center border-2 border-dashed border-black/10 rounded-3xl bg-white/50">
                <i data-lucide="layout-grid" class="w-12 h-12 text-[#5c4432]/30 mb-4"></i>
                <h3 class="text-lg font-bold text-[#2c1f16]">Belum ada Menu</h3>
                <p class="text-sm text-[#5c4432] mt-1">Silakan tambahkan menu dan resep bahan bakunya.</p>
            </div>
        @endforelse

        <div x-show="visibleCount === 0" x-transition.opacity class="col-span-full py-24 flex flex-col items-center justify-center border-2 border-dashed border-black/10 rounded-3xl bg-white/50" style="display: none;">
            <i data-lucide="search-x" class="w-12 h-12 text-[#5c4432]/30 mb-4"></i>
            <h3 class="text-lg font-bold text-[#2c1f16]">Tidak ada yang cocok</h3>
            <p class="text-sm text-[#5c4432] mt-1">
                @if($filter === 'active') Tidak ada menu yang sedang aktif. 
                @elseif($filter === 'inactive') Tidak ada menu yang diarsipkan. @endif
            </p>
        </div>

    </div>
</div>
@endsection