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

        .modal-scrollbar {
            scrollbar-width: thin;
        }

        .modal-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .modal-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d1cc;
            border-radius: 999px;
        }

        .modal-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

</head>

<body class="font-sans bg-[#f7f7f5] text-[#2f2f2f]">

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
            <div class="bg-[#2f2f2f]/75 backdrop-blur-md border border-white/10 text-white
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
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/[0.06]">Dashboard</a>
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/[0.06]">Stok</a>
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/[0.06]">Produksi</a>
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/[0.06]">Armada</a>
                        <a href="#" class="block px-3 py-2 rounded-xl hover:bg-white/[0.06]">Laporan</a>

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
                    bg-[#2f2f2f]/75 backdrop-blur-md
                    border border-white/[0.04]
                    text-white shadow-[0_8px_30px_rgba(0,0,0,0.12)]
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

                                    'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white'
                                        => request()->routeIs('dashboard'),

                                    'hover:bg-white/[0.06] text-white/80'
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

                                    'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white'
                                        => request()->routeIs('owner.raw-materials.*'),

                                    'hover:bg-white/[0.06] text-white/80'
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
                            <a href="#"
                                class="flex items-center h-11 rounded-xl
                                    hover:bg-white/[0.06] transition group"
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
                                    hover:bg-white/[0.06] transition group"
                            >

                                <div class="w-12 flex justify-center items-center">
                                    <i data-lucide="package-check" class="w-5 h-5"></i>
                                </div>

                                <span class="text-sm font-medium">
                                    Stok Jadi
                                </span>

                            </a>

                            <!-- ARMADA -->
                            <a href="{{ route('owner.armada') }}"
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

                            <!-- REPORT -->
                            <a href="#"
                                class="flex items-center h-11 rounded-xl
                                    hover:bg-white/[0.06] transition group"
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

            id="main-content"

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

    window.appToast = @json(
        session('toast')
    );

    const savedScrollPosition =
        sessionStorage.getItem(
            'scroll-position'
        );

    if (savedScrollPosition) {

        window.addEventListener(
            'load',
            () => {

                const content =
                    document.getElementById(
                        'main-content'
                    );

                if (content) {

                    content.scrollTop =
                        parseInt(savedScrollPosition);

                }

                sessionStorage.removeItem(
                    'scroll-position'
                );

            },
            { once: true }
        );
    }

</script>


<!-- TOAST STACK -->
<div
    x-cloak
    x-data="{}"
    class="fixed top-4 sm:top-6 left-1/2
        -translate-x-1/2
        z-50
        flex flex-col gap-3
        rounded-3xl
        items-center"

>

    <template
        x-for="toast in $store.toastManager.toasts"
        :key="toast.id"
    >

        <div
            x-show="toast.show"

            x-transition:enter="
                transition ease-out duration-300
            "
            x-transition:enter-start="
                opacity-0 -translate-y-4
            "
            x-transition:enter-end="
                opacity-100 translate-y-0
            "

            x-transition:leave="
                transition ease-in-out duration-300
            "
            x-transition:leave-start="
                opacity-100 translate-y-0
            "
            x-transition:leave-end="
                opacity-0 -translate-y-4
            "

            class="w-[92vw]
                sm:w-[26rem]
                md:w-[28rem]
                max-w-md
                rounded-3xl
                border border-[#e8e8e5]
                bg-white shadow-[0_8px_30px_rgba(0,0,0,0.12)]
                px-5 py-4"
        >

            <!-- HEADER -->
            <div class="flex items-start justify-between gap-4">

                <div>

                    <div
                        class="font-semibold"
                        :class="toast.type === 'error'
                            ? 'text-red-700'
                            : 'text-[#2f2f2f]'"
                    >
                        <template x-if="toast.type === 'undo'">

                            <span
                                x-text="
                                    toast.action === 'archived'
                                        ? 'Material Archived'
                                        : 'Material Restored'
                                "
                            ></span>

                        </template>

                        <template x-if="toast.type === 'success-message'">

                            <span x-text="toast.title"></span>

                        </template>

                        <template x-if="toast.type === 'error'">

                            <span x-text="toast.title"></span>

                        </template>
                        
                    </div>

                    <div class="text-xs font-medium uppercase
                        tracking-wide text-[#8a8a8a] mt-1">

                        <template x-if="toast.type === 'undo'">
                            
                            <div>

                                <span
                                    class="font-medium"
                                    x-text="toast.materialName"
                                ></span>

                                <span
                                    x-text="
                                        toast.action === 'archived'
                                            ? ' archived.'
                                            : ' restored.'
                                    "
                                ></span>

                            </div>

                        </template>

                        <template x-if="toast.type === 'error'">

                            <div x-text="toast.message"></div>

                        </template>

                        <template x-if="toast.type === 'success-message'">

                            <div x-text="toast.message"></div>

                        </template>

                    </div>

                </div>

                <div
                    class="
                        text-xs
                        font-medium
                        tabular-nums
                        text-[#8a8a8a]
                    "
                    x-text="toast.seconds + 's'"
                ></div>

            </div>

            <!-- ACTION -->
            <div
                x-show="toast.type === 'undo'"
                class="mt-4 flex justify-end"
            >

                <button
                    @click="$store.toastManager.undoToast(toast)"

                    :disabled="toast.processing"

                    class="inline-flex items-center justify-center
                        h-10 px-4 rounded-2xl
                        bg-[#2f2f2f] text-white
                        text-sm font-medium
                        hover:opacity-90 transition duration-200"

                    :class="{
                        'opacity-50 cursor-not-allowed hover:opacity-50':
                            toast.processing
                    }"
                >
                    Undo
                </button>

            </div>

        </div>

    </template>

</div>

</body>
</html>