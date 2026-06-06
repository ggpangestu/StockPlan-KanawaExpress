<a href="{{ route('armada.sessions.index') }}"
    @class([
        'flex items-center h-11 rounded-xl transition group',
        'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white' => request()->routeIs('armada.sessions.*'),
        'hover:bg-white/[0.06] text-white/80' => !request()->routeIs('armada.sessions.*'),
    ])
    >

    <div class="w-12 flex justify-center items-center">
        <i data-lucide="truck" class="w-5 h-5"></i>
    </div>

    <span class="text-sm font-medium">
        Sesi Armada
    </span>
    
</a>
