<a href="{{ route('produksi.productions.index') }}"
    @class([
        'flex items-center h-11 rounded-xl transition group',
        'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white' => request()->routeIs('produksi.productions.*'),
        'hover:bg-white/[0.06] text-white/80' => !request()->routeIs('produksi.productions.*'),
    ])
    >

    <div class="w-12 flex justify-center items-center">
        <i data-lucide="chef-hat" class="w-5 h-5"></i>
    </div>

    <span class="text-sm font-medium">
        Antrean Produksi
    </span>
    
</a>

<a href="{{ route('produksi.returns.index') }}"
    @class([
        'flex items-center h-11 rounded-xl transition group',
        'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white' => request()->routeIs('produksi.returns.*'),
        'hover:bg-white/[0.06] text-white/80' => !request()->routeIs('produksi.returns.*'),
    ])
    >

    <div class="w-12 flex justify-center items-center">
        <i data-lucide="rotate-ccw-square" class="w-5 h-5"></i>
    </div>

    <span class="text-sm font-medium">
        Retur Armada
    </span>
    
</a>
