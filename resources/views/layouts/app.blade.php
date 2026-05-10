<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="font-sans bg-[#f5efe6] text-[#2c1f16]">

<!-- ================= ROOT ================= -->
<div class="min-h-screen">

    <!-- ================= MOBILE ================= -->
    <div class="md:hidden"
        x-data="{
            open:false,
            animating:false,
            toggle() {
                if (this.animating) return;
                this.open = !this.open;
                smoothExpand(this.$refs.menu, this.open, this);
            }
        }">

        <!-- HEADER / MENU -->
        <div class="p-6">
            <div class="bg-black/40 backdrop-blur-xl border border-white/10 text-white
                        rounded-3xl overflow-hidden shadow-lg">

                <div class="flex items-center justify-between px-4 py-3">
                    <img src="{{ asset('images/logo.png') }}" 
                    alt="Logo" 
                    class="h-8 object-contain">

                    <button @click="toggle()">
                        <span x-show="!open">☰</span>
                        <span x-show="open">✕</span>
                    </button>
                </div>

                <div x-ref="menu"
                    style="height:0; overflow:hidden;">

                    <div class="px-4 pb-4 pt-2 space-y-2">
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/10">Dashboard</a>
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/10">Stok</a>
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/10">Produksi</a>
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/10">Armada</a>
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/10">Laporan</a>

                        <div class="border-t border-white/10 mt-3 pt-3">
                            <div class="text-sm opacity-70 mb-2">
                                {{ auth()->user()->name }}
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-3 py-2 rounded-xl hover:bg-red-500/70 transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="px-6 pb-6">
            @yield('content')
        </div>

    </div>

    <!-- ================= DESKTOP ================= -->
    <div class="hidden md:flex h-screen overflow-hidden"
        x-data="{ collapse:false }">

        <!-- SIDEBAR -->
        <aside
            :class="collapse ? 'w-20' : 'w-64'"
            class="m-4 flex flex-col rounded-3xl
                bg-black/40 backdrop-blur-xl border border-white/10 text-white
                shadow-xl transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">

            <!-- ================= TOP ================= -->
            <div>

                <!-- HEADER -->
                <div class="relative flex items-center h-12 px-3 mt-3">

                    <!-- LOGO -->
                    <div 
                        x-show="!collapse"
                        x-transition.opacity
                        class="absolute left-6"
                    >
                        <img src="{{ asset('images/logo.png') }}" class="h-10">
                    </div>

                    <!-- TOGGLE -->
                    <button 
                        @click="collapse = !collapse"
                        class="ml-auto w-12 mr-1 flex justify-center items-center"
                    >
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>

                </div>

                <!-- MENU -->
                <nav class="mt-2 space-y-1">

                    <!-- ITEM -->
                    <a href="#" class="relative flex items-center h-11 mx-3 rounded-xl hover:bg-white/10 transition">

                        <div class="w-12 pl-1 flex justify-center items-center">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        </div>

                        <span 
                            x-show="!collapse"
                            x-transition.opacity
                            class="absolute left-14 whitespace-nowrap"
                        >
                            Dashboard
                        </span>
                    </a>

                    <a href="#" class="relative flex items-center h-11 mx-3 rounded-xl hover:bg-white/10 transition">
                        <div class="w-12 pl-1 flex justify-center items-center">
                            <i data-lucide="package" class="w-5 h-5"></i>
                        </div>
                        <span x-show="!collapse" x-transition.opacity class="absolute left-14 whitespace-nowrap">
                            Stok
                        </span>
                    </a>

                    <a href="#" class="relative flex items-center h-11 mx-3 rounded-xl hover:bg-white/10 transition">
                        <div class="w-12 pl-1 flex justify-center items-center">
                            <i data-lucide="factory" class="w-5 h-5"></i>
                        </div>
                        <span x-show="!collapse" x-transition.opacity class="absolute left-14 whitespace-nowrap">
                            Produksi
                        </span>
                    </a>

                    <a href="#" class="relative flex items-center h-11 mx-3 rounded-xl hover:bg-white/10 transition">
                        <div class="w-12 pl-1 flex justify-center items-center">
                            <i data-lucide="truck" class="w-5 h-5"></i>
                        </div>
                        <span x-show="!collapse" x-transition.opacity class="absolute left-14 whitespace-nowrap">
                            Armada
                        </span>
                    </a>

                    <a href="#" class="relative flex items-center h-11 mx-3 rounded-xl hover:bg-white/10 transition">
                        <div class="w-12 pl-1 flex justify-center items-center">
                            <i data-lucide="truck" class="w-5 h-5"></i>
                        </div>
                        <span x-show="!collapse" x-transition.opacity class="absolute left-14 whitespace-nowrap">
                            Armada
                        </span>
                    </a>

                    <a href="#" class="relative flex items-center h-11 mx-3 rounded-xl hover:bg-white/10 transition">
                        <div class="w-12 pl-1 flex justify-center items-center">
                            <i data-lucide="truck" class="w-5 h-5"></i>
                        </div>
                        <span x-show="!collapse" x-transition.opacity class="absolute left-14 whitespace-nowrap">
                            Armada
                        </span>
                    </a>

                </nav>
            </div>

            <!-- ================= BOTTOM ================= -->
            <div class="mt-auto px-3 py-4 border-t border-white/10">

                {{-- <!-- USER -->
                <div class="relative flex items-center h-11">

                    <!-- ICON SLOT -->
                    <div class="w-12 flex justify-center items-center">
                        <div class="w-8 h-8 rounded-full bg-[#c8a27c]"></div>
                    </div>

                    <!-- TEXT -->
                    <span 
                        x-show="!collapse"
                        x-transition.opacity
                        class="absolute left-14 text-sm whitespace-nowrap"
                    >
                        {{ auth()->user()->name }}
                    </span>
                </div> --}}

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf

                    <button type="submit"
                        class="relative flex items-center h-11 w-full rounded-xl hover:bg-red-500/70 transition text-left">

                        <!-- ICON SLOT (IDENTIK) -->
                        <div class="w-12 pl-1 flex justify-center items-center">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </div>

                        <!-- TEXT (IDENTIK) -->
                        <span 
                            x-show="!collapse"
                            x-transition.opacity
                            class="absolute left-12 whitespace-nowrap"
                        >
                            Logout
                        </span>

                    </button>
                </form>

            </div>
        </aside>

        <!-- CONTENT -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>

    </div>

</div>

<!-- ================= JS ================= -->
<script>

    function smoothExpand(el, open, ctx) {
        const duration = 800;
        const start = el.offsetHeight;
        const end = open ? el.scrollHeight : 0;
        const startTime = performance.now();

        function ease(t) {
            return t < 0.5
                ? 4*t*t*t
                : 1 - Math.pow(-2*t + 2, 3)/2;
        }

        function frame(time) {
            const p = Math.min((time - startTime)/duration, 1);
            const e = ease(p);

            el.style.height = (start + (end-start)*e) + 'px';

            if (p < 1) requestAnimationFrame(frame);
            else {
                el.style.height = end + 'px';
                ctx.animating = false;
            }
        }

        ctx.animating = true;
        requestAnimationFrame(frame);
    }

    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();
    });

    document.addEventListener("alpine:init", () => {
        setTimeout(() => lucide.createIcons(), 0);
    });
</script>


</body>
</html>