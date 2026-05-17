<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
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
    <div
        x-cloak
        class="hidden md:block h-screen relative overflow-hidden"
        x-data="{
            open: false,

            init() {
                this.open = JSON.parse(
                    localStorage.getItem('sidebar-open')
                ) ?? false;
            },

            toggle() {
                this.open = !this.open;

                localStorage.setItem(
                    'sidebar-open',
                    JSON.stringify(this.open)
                );
            }
        }"
    >

        <!-- ================= FLOATING MENU ================= -->
        <div
            class="fixed top-6 left-6 z-50"
        >

            <!-- MENU CONTAINER -->
            <div
                :class="open
                    ? 'w-48 h-[calc(100vh-3rem)] rounded-[2rem]'
                    : 'w-14 h-14 rounded-2xl'
                "
                class="overflow-hidden overscroll-none
                    flex flex-col
                    bg-black/40 backdrop-blur-xl
                    border border-white/10
                    text-white shadow-2xl
                    transition-[width,height,border-radius]
                    duration-500
                    ease-[cubic-bezier(0.22,1,0.36,1)]"
            >

                <!-- ================= BURGER ================= -->
                <button
                    @click="toggle()"
                    class="w-14 h-14 flex items-center justify-center shrink-0"
                >

                    <div class="flex flex-col justify-center items-center gap-1.5">

                        <span
                            :class="open ? 'rotate-45 translate-y-2' : ''"
                            class="w-6 h-0.5 bg-white rounded transition-all duration-300"
                        ></span>

                        <span
                            :class="open ? 'opacity-0' : ''"
                            class="w-6 h-0.5 bg-white rounded transition-all duration-300"
                        ></span>

                        <span
                            :class="open ? '-rotate-45 -translate-y-2' : ''"
                            class="w-6 h-0.5 bg-white rounded transition-all duration-300"
                        ></span>

                    </div>

                </button>

                <!-- ================= SIDEBAR CONTENT ================= -->
                <div
                    x-show="open"
                    x-cloak
                    x-transition.opacity.duration.200ms
                    class="flex flex-col flex-1 min-h-0"
                >

                    <!-- ================= MENU ================= -->
                    <nav class="flex-1 px-3 space-y-1 overflow-y-auto no-scrollbar">
                        @if(auth()->user()->role === 'owner')

                            <!-- DASHBOARD -->
                            <a
                                href="{{ route('dashboard') }}"

                                @class([
                                    'flex items-center h-11 rounded-xl transition group',

                                    'bg-white/15 shadow-lg text-white'
                                        => request()->routeIs('dashboard'),

                                    'hover:bg-white/10 text-white/80'
                                        => !request()->routeIs('dashboard'),
                                ])
                            >

                                <div class="w-12 flex justify-center items-center">
                                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                </div>

                                <span class="text-sm font-medium">
                                    Dashboard
                                </span>

                            </a>

                            <!-- RAW MATERIAL -->
                            <a
                                href="{{ route('owner.raw-materials.index') }}"

                                @class([
                                    'flex items-center h-11 rounded-xl transition group',

                                    'bg-white/15 shadow-lg text-white'
                                        => request()->routeIs('owner.raw-materials.*'),

                                    'hover:bg-white/10 text-white/80'
                                        => !request()->routeIs('owner.raw-materials.*'),

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
                            <a href="#"
                                class="flex items-center h-11 rounded-xl
                                    hover:bg-white/10 transition group"
                            >

                                <div class="w-12 flex justify-center items-center">
                                    <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                                </div>

                                <span class="text-sm font-medium">
                                    Menu
                                </span>

                            </a>

                            <!-- PRODUKSI -->
                            <a href="#"
                                class="flex items-center h-11 rounded-xl
                                    hover:bg-white/10 transition group"
                            >

                                <div class="w-12 flex justify-center items-center">
                                    <i data-lucide="factory" class="w-5 h-5"></i>
                                </div>

                                <span class="text-sm font-medium">
                                    Produksi
                                </span>

                            </a>

                            <!-- STOK JADI -->
                            <a href="#"
                                class="flex items-center h-11 rounded-xl
                                    hover:bg-white/10 transition group"
                            >

                                <div class="w-12 flex justify-center items-center">
                                    <i data-lucide="package-check" class="w-5 h-5"></i>
                                </div>

                                <span class="text-sm font-medium">
                                    Stok Jadi
                                </span>

                            </a>

                            <!-- ARMADA -->
                            <a href="#"
                                class="flex items-center h-11 rounded-xl
                                    hover:bg-white/10 transition group"
                            >

                                <div class="w-12 flex justify-center items-center">
                                    <i data-lucide="truck" class="w-5 h-5"></i>
                                </div>

                                <span class="text-sm font-medium">
                                    Armada
                                </span>

                            </a>

                            <!-- REPORT -->
                            <a href="#"
                                class="flex items-center h-11 rounded-xl
                                    hover:bg-white/10 transition group"
                            >

                                <div class="w-12 flex justify-center items-center">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>

                                <span class="text-sm font-medium">
                                    Report
                                </span>

                            </a>

                        @endif

                    </nav>

                    <!-- ================= BOTTOM ================= -->
                    <div class="p-3 border-t border-white/10">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button
                                type="submit"
                                class="w-full flex items-center h-11 rounded-xl
                                    hover:bg-red-500/70 transition"
                            >

                                <div class="w-12 flex justify-center items-center">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                </div>

                                <span class="text-sm font-medium">
                                    Logout
                                </span>

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <!-- ================= CONTENT ================= -->
        <main
            :class="open ? 'md:pl-60' : 'md:pl-28'"
            class="h-screen overflow-y-auto
                pt-6 pr-6 pb-6
                md:pt-8 md:pr-8 md:pb-8
                transition-all duration-500
                ease-[cubic-bezier(0.22,1,0.36,1)]"
        >
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