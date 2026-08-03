<!-- RAW MATERIAL -->
<a 
    href="{{ route('owner.raw-materials.index') }}"

    @class([
        'flex items-center h-11 rounded-xl transition group',
        'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white' => request()->routeIs('owner.raw-materials.*'),
        'hover:bg-white/[0.06] text-white/80' => !request()->routeIs('owner.raw-materials.*'),
    ])
>
    <div class="w-12 flex justify-center items-center">
        <i data-lucide="boxes" class="w-5 h-5"></i>
    </div>
    <span class="text-sm font-medium">
        Raw Material
    </span>
</a>

<!-- MENU -->
<a href="{{ route('owner.menus.index') }}"
    @class([
        'flex items-center h-11 rounded-xl transition group',
        'bg-white/15 shadow-lg text-white' => request()->routeIs('owner.menus.*'),
        'hover:bg-white/10 text-white/80' => !request()->routeIs('owner.menus.*'),
    ])
>
    <div class="w-12 flex justify-center items-center">
        <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
    </div>
    <span class="text-sm font-medium">
        Menu
    </span>
</a>

<!-- PRODUKSI -->
<a href="{{ route('owner.productions.index') }}"
    @class([
        'flex items-center h-11 rounded-xl transition group',
        'bg-white/15 shadow-lg text-white' => request()->routeIs('owner.productions.*'),
        'hover:bg-white/10 text-white/80' => !request()->routeIs('owner.productions.*'),
    ])
>
    <div class="w-12 flex justify-center items-center">
        <i data-lucide="factory" class="w-5 h-5"></i>
    </div>
    <span class="text-sm font-medium">
        Produksi
    </span>
</a>

<!-- STOK JADI -->
<a href="{{ route('owner.stok-jadi.index') }}"
    class="flex items-center h-11 rounded-xl hover:bg-white/[0.06] text-white/80 transition group"
>
    <div class="w-12 flex justify-center items-center">
        <i data-lucide="package-check" class="w-5 h-5"></i>
    </div>
    <span class="text-sm font-medium">
        Stok Jadi
    </span>
</a>

<!-- ARMADA -->
<a href="{{ route('owner.armada.index') }}"
    @class([
        'flex items-center h-11 rounded-xl transition group',
        'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white' => request()->routeIs('owner.armada'),
        'hover:bg-white/[0.06] text-white/80' => !request()->routeIs('owner.armada'),
    ])
>
    <div class="w-12 flex justify-center items-center">
        <i data-lucide="truck" class="w-5 h-5"></i>
    </div>
    <span class="text-sm font-medium">
        Armada
    </span>
</a>

<!-- ARMADA SESSIONS -->
<a href="{{ route('owner.armada-sessions.index') }}"
    @class([
        'flex items-center h-11 rounded-xl transition group',
        'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white' => request()->routeIs('owner.armada-sessions.*'),
        'hover:bg-white/[0.06] text-white/80' => !request()->routeIs('owner.armada-sessions.*'),
    ])
>
    <div class="w-12 flex justify-center items-center">
        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
    </div>
    <span class="text-sm font-medium">
        Sesi Armada
    </span>
</a>

<!-- REPORT -->
<div
    x-data="{
        open:
            {{ request()->routeIs('owner.reports.*')
                ? 'true'
                : 'false'
            }}
    }"
>

    <button
        @click="open = !open"

        @class([
            'w-full flex items-center h-11 rounded-xl transition group',

            'text-white'
                => request()->routeIs('owner.reports.*'),

            'hover:bg-white/[0.06] text-white/80'
                => !request()->routeIs('owner.reports.*'),
        ])
    >

        <div class="w-12 flex justify-center items-center">
            <i data-lucide="file-text"
                class="w-5 h-5">
            </i>
        </div>

        <span class="text-sm font-medium">
            Report
        </span>

        <div class="ml-auto pr-4">

            <i
                data-lucide="chevron-down"

                class="w-4 h-4 transition-transform"

                :class="{
                    'rotate-180': open
                }"
            ></i>

        </div>

    </button>

    <div
    
        x-show="open"
        x-collapse

        class="
            ml-4
            mt-1
            pl-3
            border-l border-white/10
            space-y-1
        "
    >

        <a
            href="{{ route('owner.reports.transactions') }}"

            @class([
                'flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition',

                'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white'
                    => request()->routeIs('owner.reports.transactions'),

                'hover:bg-white/[0.06] text-white/80'
                    => !request()->routeIs('owner.reports.transactions'),
                    
            ])
        >

            <span>
                Transactions
            </span>

        </a>

        <a
            href="{{ route('owner.reports.productions') }}"

            @class([
                'flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition',

                'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white'
                    => request()->routeIs('owner.reports.productions'),

                'hover:bg-white/[0.06] text-white/80'
                    => !request()->routeIs('owner.reports.productions'),
                    
            ])
        >

            <span>
                Production
            </span>

        </a>

        {{-- <a
            href="#"

            class="flex items-center gap-2
                px-3 py-2
                rounded-lg
                text-sm
                text-white/70
                hover:text-white
                hover:bg-white/[0.06]
                transition"
        >

            <span>
                Finished Goods
            </span>

        </a> --}}

        {{-- <a
            href="#"

            class="flex items-center gap-2
                px-3 py-2
                rounded-lg
                text-sm
                text-white/70
                hover:text-white
                hover:bg-white/[0.06]
                transition"
        >

            <span>
                Finance
            </span>

        </a> --}}
    </div>

</div>
