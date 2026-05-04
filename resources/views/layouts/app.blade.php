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
            class="m-4 flex flex-col justify-between rounded-3xl
            bg-black/40 backdrop-blur-xl border border-white/10 text-white
            shadow-xl transition-all duration-500">

            <!-- TOP -->
            <div>
                <div class="flex items-center justify-between px-4 py-4">
                    <div class="flex items-center gap-2">

                        <!-- FULL LOGO -->
                        <img 
                            x-show="!collapse"
                            x-transition
                            src="{{ asset('images/logo.png') }}" 
                            class="h-10 object-contain"
                            alt="Logo"
                        >

                    </div>

                    <button @click="collapse = !collapse">
                        ☰
                    </button>
                </div>

                <nav class="px-2 space-y-2">
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
                        <span x-show="!collapse">Dashboard</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition">
                        <i data-lucide="package" class="w-5 h-5"></i>
                        <span x-show="!collapse">Stok</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition">
                        <i data-lucide="factory" class="w-5 h-5"></i>
                        <span x-show="!collapse">Produksi</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition">
                        <i data-lucide="truck" class="w-5 h-5"></i>
                        <span x-show="!collapse">Armada</span>
                    </a>
                                    </nav>
            </div>

            <!-- BOTTOM -->
            <div class="px-3 py-4 border-t border-white/10">

                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-[#c8a27c]"></div>

                    <div x-show="!collapse" class="text-sm">
                        {{ auth()->user()->name }}
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-red-500/70 transition">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span x-show="!collapse">Logout</span>
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