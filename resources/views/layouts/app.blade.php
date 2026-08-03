<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <script>
        (function () {
            var open = JSON.parse(localStorage.getItem('sidebar-open') || 'false');
            window.__sidebarOpen = open;

            // Inject style ke elemen sidebar sebelum DOMContentLoaded
            // menggunakan style tag agar berlaku sebelum Alpine mount
            var css = open
                ? '#sidebar-shell{width:12rem;height:calc(100vh - 3rem);border-radius:2rem}'
                : '#sidebar-shell{width:3.5rem;height:3.5rem;border-radius:1rem}';

            var s = document.createElement('style');
            s.id = 'sidebar-init-style';
            s.textContent = css;
            document.head.appendChild(s);
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Sembunyikan elemen Alpine sebelum di-mount agar tidak flicker */
        [x-cloak] { display: none !important; }

        /*
         * Ukuran awal sidebar di-set via script inline di <head> (id: sidebar-init-style).
         * Setelah Alpine mount dan user klik burger, style ini di-remove dan
         * Alpine mengambil alih sepenuhnya dengan transition aktif.
         */

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .modal-scrollbar { scrollbar-width: thin; }
        .modal-scrollbar::-webkit-scrollbar { width: 8px; }
        .modal-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d1cc;
            border-radius: 999px;
        }
        .modal-scrollbar::-webkit-scrollbar-track { background: transparent; }
    </style>

</head>

{{-- Body bersih — tidak perlu no-transition karena sidebar sudah di-set via injected style --}}
<body class="font-sans bg-[#f7f7f5] text-[#2f2f2f]">

    <!-- ================= ROOT ================= -->
    <div
        class="min-h-screen"
        x-cloak
        x-data="{
            sidebarOpen: window.__sidebarOpen,

            init() {
                // Tidak ada animasi saat load — sidebar sudah di posisi benar
                // via injected style di <head>. Kita hanya lepas style itu
                // setelah Alpine siap agar Alpine bisa mengambil alih sizing.
                this.$nextTick(() => {
                    var initStyle = document.getElementById('sidebar-init-style');
                    if (initStyle) initStyle.remove();
                });
            },

            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                localStorage.setItem('sidebar-open', JSON.stringify(this.sidebarOpen));
            },

            toggleSidebarAnimated() {
                const shell = this.$refs.shell;
                const main = this.$refs.main;
                const burger = this.$refs.burger;

                const dur = 'duration-500';
                const ease = 'ease-[cubic-bezier(0.22,1,0.36,1)]';

                shell.classList.add(
                    'transition-[width,height,border-radius]',
                    dur,
                    ease
                );

                main.classList.add(
                    'transition-[padding]',
                    dur,
                    ease
                );

                burger.querySelectorAll('span').forEach(el => {
                    el.classList.add(
                        'transition-all',
                        'duration-300'
                    );
                });

                this.toggleSidebar();

                shell.addEventListener(
                    'transitionend',
                    function cleanup(e) {

                        if (e.propertyName !== 'width') {
                            return;
                        }

                        shell.classList.remove(
                            'transition-[width,height,border-radius]',
                            dur,
                            ease
                        );

                        main.classList.remove(
                            'transition-[padding]',
                            dur,
                            ease
                        );

                        burger.querySelectorAll('span').forEach(el => {
                            el.classList.remove(
                                'transition-all',
                                'duration-300'
                            );
                        });

                        shell.removeEventListener(
                            'transitionend',
                            cleanup
                        );
                    }
                );
            }
        }"
    >

        <!-- ================= MOBILE ================= -->
        <div
            class="md:hidden"
            x-data="{
                open: false,
                animating: false,
                toggle() {
                    if (this.animating) return;
                    this.open = !this.open;
                    smoothExpand(this.$refs.menu, this.open, this);
                }
            }"
        >

            <!-- NAVBAR MOBILE -->
            <div class="p-6">
                <div class="bg-[#2f2f2f]/75 backdrop-blur-md border border-white/10 text-white
                            rounded-3xl overflow-hidden shadow-lg">

                    <div class="flex items-center justify-between px-4 py-3">
                        <img
                            src="{{ asset('images/logo.png') }}"
                            alt="Logo"
                            class="h-8 object-contain"
                        >

                        <button @click="toggle()" aria-label="Toggle menu">
                            <span x-show="!open" x-cloak>☰</span>
                            <span x-show="open"  x-cloak>✕</span>
                            {{-- Tampilkan burger default saat Alpine belum mount --}}
                            <span class="[.hydrated_&]:hidden">☰</span>
                        </button>
                    </div>

                    <!-- MENU EXPAND -->
                    <div x-ref="menu" style="height:0; overflow:hidden;">
                        <div class="px-4 pb-4 pt-2 space-y-2">

                            {{-- Dashboard — semua role --}}
                            <a href="{{ route('dashboard') }}"
                               @class([
                                   'block px-3 py-2 rounded-xl hover:bg-white/[0.06]',
                                   'bg-white/[0.08]' => request()->routeIs('dashboard'),
                               ])>
                                Dashboard
                            </a>

                            {{-- ===== OWNER ===== --}}
                            @if(auth()->user()->role === 'owner')

                                <a href="{{ route('owner.raw-materials.index') }}"
                                   @class([
                                       'block px-3 py-2 rounded-xl hover:bg-white/[0.06]',
                                       'bg-white/[0.08]' => request()->routeIs('owner.raw-materials.*'),
                                   ])>
                                    Stok Bahan
                                </a>

                                <a href="{{ route('owner.productions.index') }}"
                                   @class([
                                       'block px-3 py-2 rounded-xl hover:bg-white/[0.06]',
                                       'bg-white/[0.08]' => request()->routeIs('owner.productions.*'),
                                   ])>
                                    Produksi
                                </a>

                                <a href="{{ route('owner.stok-jadi.index') }}"
                                   @class([
                                       'block px-3 py-2 rounded-xl hover:bg-white/[0.06]',
                                       'bg-white/[0.08]' => request()->routeIs('owner.stok-jadi.*'),
                                   ])>
                                    Stok Jadi
                                </a>

                                <a href="{{ route('owner.armada.index') }}"
                                   @class([
                                       'block px-3 py-2 rounded-xl hover:bg-white/[0.06]',
                                       'bg-white/[0.08]' => request()->routeIs('owner.armada.*') || request()->routeIs('owner.armada-sessions.*'),
                                   ])>
                                    Armada
                                </a>

                                <a href="{{ route('owner.reports.transactions') }}"
                                   @class([
                                       'block px-3 py-2 rounded-xl hover:bg-white/[0.06]',
                                       'bg-white/[0.08]' => request()->routeIs('owner.reports.*'),
                                   ])>
                                    Laporan
                                </a>

                            {{-- ===== PRODUKSI ===== --}}
                            @elseif(auth()->user()->role === 'produksi')

                                <a href="{{ route('produksi.productions.index') }}"
                                   @class([
                                       'block px-3 py-2 rounded-xl hover:bg-white/[0.06]',
                                       'bg-white/[0.08]' => request()->routeIs('produksi.productions.*'),
                                   ])>
                                    Produksi
                                </a>

                                <a href="{{ route('produksi.returns.index') }}"
                                   @class([
                                       'block px-3 py-2 rounded-xl hover:bg-white/[0.06]',
                                       'bg-white/[0.08]' => request()->routeIs('produksi.returns.*'),
                                   ])>
                                    Return
                                </a>

                            {{-- ===== ARMADA ===== --}}
                            @elseif(auth()->user()->role === 'armada')

                                <a href="{{ route('armada.sessions.index') }}"
                                   @class([
                                       'block px-3 py-2 rounded-xl hover:bg-white/[0.06]',
                                       'bg-white/[0.08]' => request()->routeIs('armada.sessions.*'),
                                   ])>
                                    Sesi Armada
                                </a>

                            @endif

                            {{-- User info + Logout --}}
                            <div class="border-t border-white/10 mt-3 pt-3">
                                <div class="text-sm opacity-70 mb-2">
                                    {{ auth()->user()->name }}
                                </div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full text-left px-3 py-2 rounded-xl hover:bg-red-500/70 transition"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- CONTENT MOBILE -->
            <div class="px-6 pb-6">
                @yield('content')
            </div>

        </div>

        <!-- ================= DESKTOP ================= -->
        <div class="hidden md:block">

            <!-- ================= FLOATING SIDEBAR ================= -->
            <div class="fixed top-6 left-6 z-50">

                <!-- SIDEBAR CONTAINER
                     id="sidebar-shell" dipakai script <head> untuk inject ukuran awal.
                     Tidak ada class transition di sini — transition di-inject Alpine
                     via x-ref hanya saat user klik, lalu di-remove setelah animasi selesai.
                -->
                <div
                    id="sidebar-shell"
                    x-ref="shell"
                    :class="sidebarOpen
                        ? 'w-48 h-[calc(100vh-3rem)] rounded-[2rem]'
                        : 'w-14 h-14 rounded-2xl'"
                    class="
                        overflow-hidden overscroll-none flex flex-col
                        bg-[#2f2f2f]/75 backdrop-blur-md
                        border border-white/[0.04] text-white
                        shadow-[0_8px_30px_rgba(0,0,0,0.12)]
                    "
                >

                    <!-- TOMBOL BURGER -->
                    <button
                        x-cloak
                        @click="toggleSidebarAnimated()"
                        
                        class="w-14 h-14 flex items-center justify-center shrink-0"
                        :aria-label="sidebarOpen ? 'Tutup sidebar' : 'Buka sidebar'"
                        :aria-expanded="sidebarOpen"
                    >
                        <div
                            x-ref="burger"
                            class="flex flex-col justify-center items-center gap-1.5"
                        >
                            <span
                                :class="sidebarOpen ? 'rotate-45 translate-y-2' : ''"
                                class="w-6 h-0.5 bg-white rounded"
                            ></span>
                            <span
                                :class="sidebarOpen ? 'opacity-0' : ''"
                                class="w-6 h-0.5 bg-white rounded"
                            ></span>
                            <span
                                :class="sidebarOpen ? '-rotate-45 -translate-y-2' : ''"
                                class="w-6 h-0.5 bg-white rounded"
                            ></span>
                        </div>
                    </button>

                    <!-- ISI SIDEBAR -->
                    <div
                        x-show="sidebarOpen"
                        x-cloak
                        x-transition:enter="transition-opacity duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="flex flex-col flex-1 min-h-0"
                    >

                        <!-- NAVIGASI -->
                        <nav class="flex-1 px-3 space-y-1 overflow-y-auto no-scrollbar">

                            <!-- DASHBOARD (semua role) -->
                            <a
                                href="{{ route('dashboard') }}"
                                @class([
                                    'flex items-center h-11 rounded-xl transition group',
                                    'bg-white/[0.08] backdrop-blur-sm shadow-lg text-white'   => request()->routeIs('dashboard'),
                                    'hover:bg-white/[0.06] text-white/80'                     => !request()->routeIs('dashboard'),
                                ])
                            >
                                <div class="w-12 flex justify-center items-center">
                                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                </div>
                                <span class="text-sm font-medium">Dashboard</span>
                            </a>

                            <!-- MENU PER ROLE -->
                            @if(auth()->user()->role === 'owner')
                                @include('layouts.sidebar.owner')
                            @elseif(auth()->user()->role === 'produksi')
                                @include('layouts.sidebar.produksi')
                            @elseif(auth()->user()->role === 'armada')
                                @include('layouts.sidebar.armada')
                            @endif

                        </nav>

                        <!-- LOGOUT -->
                        <div class="p-3 border-t border-white/10">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full flex items-center h-11 rounded-xl hover:bg-red-500/70 transition"
                                >
                                    <div class="w-12 flex justify-center items-center">
                                        <i data-lucide="log-out" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-sm font-medium">Logout</span>
                                </button>
                            </form>
                        </div>

                    </div>

                </div>

            </div>

            <!-- ================= CONTENT DESKTOP ================= -->
            <main
                x-ref="main"
                :style="sidebarOpen ? 'padding-left: 15rem' : 'padding-left: 7rem'"
                class="min-h-screen pt-6 pr-6 pb-6 md:pt-8 md:pr-8 md:pb-8"
            >
                @yield('content')
            </main>

        </div>

    </div>


    <!-- ================= JS ================= -->
    <script>

        // Animasi expand/collapse untuk navbar mobile
        function smoothExpand(el, open, ctx) {
            const duration = 800;
            const start    = el.offsetHeight;
            const end      = open ? el.scrollHeight : 0;
            const startTime = performance.now();

            function ease(t) {
                return t < 0.5
                    ? 4 * t * t * t
                    : 1 - Math.pow(-2 * t + 2, 3) / 2;
            }

            function frame(time) {
                const p = Math.min((time - startTime) / duration, 1);
                el.style.height = (start + (end - start) * ease(p)) + 'px';

                if (p < 1) {
                    requestAnimationFrame(frame);
                } else {
                    el.style.height = end + 'px';
                    ctx.animating  = false;
                }
            }

            ctx.animating = true;
            requestAnimationFrame(frame);
        }

        // Kirim toast dari session PHP ke Alpine store
        window.appToast = @json(session('toast'));

        // Pulihkan posisi scroll setelah navigasi
        const savedScrollPosition = sessionStorage.getItem('scroll-position');

        if (savedScrollPosition) {
            window.addEventListener('load', () => {
                const content = document.getElementById('main-content');
                if (content) {
                    content.scrollTop = parseInt(savedScrollPosition);
                }
                sessionStorage.removeItem('scroll-position');
            }, { once: true });
        }

    </script>


    <!-- ================= TOAST STACK ================= -->
    <div
        x-cloak
        x-data="{}"
        class="fixed top-4 sm:top-6 left-1/2 -translate-x-1/2
               z-50 flex flex-col gap-3 items-center"
    >
        <template x-for="toast in $store.toastManager.toasts" :key="toast.id">

            <div
                x-show="toast.show"

                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"

                x-transition:leave="transition ease-in-out duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"

                class="w-[92vw] sm:w-[26rem] md:w-[28rem] max-w-md
                       rounded-3xl border border-[#e8e8e5]
                       bg-white shadow-[0_8px_30px_rgba(0,0,0,0.12)]
                       px-5 py-4"
            >

                <!-- HEADER TOAST -->
                <div class="flex items-start justify-between gap-4">

                    <div>
                        <div
                            class="font-semibold"
                            :class="toast.type === 'error' ? 'text-red-700' : 'text-[#2f2f2f]'"
                        >
                            <template x-if="toast.type === 'undo'">
                                <span x-text="toast.action === 'archived' ? 'Material Archived' : 'Material Restored'"></span>
                            </template>
                            <template x-if="toast.type === 'success-message'">
                                <span x-text="toast.title"></span>
                            </template>
                            <template x-if="toast.type === 'error'">
                                <span x-text="toast.title"></span>
                            </template>
                        </div>

                        <div class="text-xs font-medium uppercase tracking-wide text-[#8a8a8a] mt-1">
                            <template x-if="toast.type === 'undo'">
                                <div>
                                    <span class="font-medium" x-text="toast.materialName"></span>
                                    <span x-text="toast.action === 'archived' ? ' archived.' : ' restored.'"></span>
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

                    <div class="text-xs font-medium tabular-nums text-[#8a8a8a]"
                        x-text="toast.seconds + 's'">
                    </div>

                </div>

                <!-- AKSI UNDO -->
                <div x-show="toast.type === 'undo'" class="mt-4 flex justify-end">
                    <button
                        @click="$store.toastManager.undoToast(toast)"
                        :disabled="toast.processing"
                        class="inline-flex items-center justify-center h-10 px-4 rounded-2xl
                            bg-[#2f2f2f] text-white text-sm font-medium
                            hover:opacity-90 transition duration-200"
                        :class="{ 'opacity-50 cursor-not-allowed hover:opacity-50': toast.processing }"
                    >
                        Undo
                    </button>
                </div>

            </div>

        </template>
    </div>

    @stack('scripts')

</body>
</html>