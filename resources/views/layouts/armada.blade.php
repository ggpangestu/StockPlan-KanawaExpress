<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        StockPlan Armada — {{ config('app.name') }}
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            color-scheme: light;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f4f1;
            color: #1f1f1f;
        }
    </style>
</head>

<body class="antialiased">

    <div class="min-h-screen">

        @yield('content')

    </div>


    {{-- =========================================================
         SESSION TOAST BRIDGE
    ========================================================== --}}
    <script>
        window.appToast = @json(session('toast'));
    </script>


    {{-- =========================================================
         TOAST STACK
    ========================================================== --}}
    <div
        x-cloak
        x-data="{}"
        class="fixed top-4 left-1/2 z-50 flex -translate-x-1/2 flex-col items-center gap-3 sm:top-6"
    >
        <template
            x-for="toast in $store.toastManager.toasts"
            :key="toast.id"
        >

            <div
                x-show="toast.show"

                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"

                x-transition:leave="transition ease-in-out duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"

                class="w-[92vw] max-w-md rounded-3xl border border-[#e8e8e5] bg-white px-5 py-4 shadow-[0_8px_30px_rgba(0,0,0,0.12)] sm:w-[26rem] md:w-[28rem]"
            >

                {{-- TOAST HEADER --}}
                <div class="flex items-start justify-between gap-4">

                    <div>

                        <div
                            class="font-semibold"
                            :class="
                                toast.type === 'error'
                                    ? 'text-red-700'
                                    : 'text-[#2f2f2f]'
                            "
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


                        <div class="mt-1 text-xs font-medium uppercase tracking-wide text-[#8a8a8a]">

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
                        class="text-xs font-medium tabular-nums text-[#8a8a8a]"
                        x-text="toast.seconds + 's'"
                    ></div>

                </div>


                {{-- UNDO ACTION --}}
                <div
                    x-show="toast.type === 'undo'"
                    class="mt-4 flex justify-end"
                >

                    <button
                        @click="$store.toastManager.undoToast(toast)"
                        :disabled="toast.processing"
                        class="inline-flex h-10 items-center justify-center rounded-2xl bg-[#2f2f2f] px-4 text-sm font-medium text-white transition duration-200 hover:opacity-90"
                        :class="{
                            'cursor-not-allowed opacity-50 hover:opacity-50':
                                toast.processing
                        }"
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