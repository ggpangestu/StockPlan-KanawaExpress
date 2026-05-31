@props([
    'title' => '',
    'maxWidth' => 'lg',
])

@php

$widthClasses = [

    'sm' => 'max-w-md',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',

];

@endphp

<div
    x-show="open"
    x-cloak

    @keydown.escape.window="open = false"

    class="fixed inset-0 z-50"
>

    <!-- BACKDROP -->
    <div
        x-show="open"
        x-transition.opacity

        @click="open = false"

        class="absolute inset-0
            bg-black/30
            backdrop-blur-sm"
    ></div>

    <!-- MODAL -->
    <div
        class="relative flex min-h-screen
            items-center justify-center
            p-4"
    >

        <div
            x-show="open"

            x-transition:enter="
                transition ease-out duration-300
            "
            x-transition:enter-start="
                opacity-0 scale-95
            "
            x-transition:enter-end="
                opacity-100 scale-100
            "

            x-transition:leave="
                transition ease-in duration-300
            "
            x-transition:leave-start="
                opacity-100 scale-100
            "
            x-transition:leave-end="
                opacity-0 scale-95
            "

            @click.stop

            class="w-full
                {{ $widthClasses[$maxWidth] }}
                max-h-[90vh]
                bg-white
                rounded-3xl
                border border-[#e8e8e5]
                shadow-[0_8px_30px_rgba(0,0,0,0.12)]
                overflow-hidden flex flex-col"
        >

            <!-- HEADER -->
            <div
                class="flex items-center justify-between
                    px-6 py-5
                    border-b border-[#e8e8e5]"
            >

                <h2
                    class="text-lg font-semibold
                        text-[#2f2f2f]"
                >
                    {{ $title }}
                </h2>

                <button
                    type="button"

                    @click="open = false"

                    class="w-9 h-9
                        rounded-xl
                        flex items-center justify-center
                        text-[#8a8a8a]
                        hover:bg-[#f3f3f1]
                        transition"
                >

                    <i
                        data-lucide="x"
                        class="w-4 h-4"
                    ></i>

                </button>

            </div>

            <!-- CONTENT -->
            <div
                class="flex-1 p-6 overflow-y-auto modal-scrollbar"
            >

                {{ $slot }}

            </div>

            <!-- FOOTER -->
            @isset($footer)

                <div
                    class="px-6 py-4
                        border-t border-[#e8e8e5]
                        bg-[#fafafa]"
                >

                    {{ $footer }}

                </div>

            @endisset

        </div>

    </div>

</div>