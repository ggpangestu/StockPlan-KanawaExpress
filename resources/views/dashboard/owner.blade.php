@extends('layouts.app')

@section('content')

<div class="space-y-8">

    <!-- TOP ALERT -->
    <div class="flex items-center justify-between text-sm text-gray-500">

        <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-[#8b6b5a] rounded-full"></div>

            <p>
                Bahan baku akan bertahan:
                <span class="font-semibold text-[#5c4432]">
                    7 hari (Good)
                </span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-[#5c4432] font-medium">
                Owner
            </span>

            <div class="w-6 h-6 rounded-full bg-gray-300"></div>
        </div>

    </div>

    <!-- HERO SECTION -->
    <div
        class="bg-[#f8f5f2] border border-[#eee] rounded-[32px] p-10 flex flex-col lg:flex-row items-center justify-between gap-10">

        <!-- LEFT -->
        <div class="max-w-xl">

            <h1 class="text-5xl font-bold leading-tight text-[#1f1f1f]">
                Welcome to Kanawa Express
            </h1>

            <p class="mt-6 text-[#5c4432] text-lg leading-relaxed">
                Manage inventory with ease and keep your coffee shop running smoothly.
            </p>

            <button
                class="mt-8 bg-[#6f5546] hover:bg-[#5c4432] transition text-white px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">

                <!-- ICON -->
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />
                </svg>

                Go to Inventory
            </button>

        </div>

        <!-- RIGHT IMAGE -->
        <div class="relative">

            <img src="{{ asset('images/kanawa-hero.png') }}"
                alt="Kanawa"
                class="w-[400px] opacity-90">

            <!-- NAME TAG -->
            <div
                class="absolute top-5 right-5 bg-[#ff6a3d] text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md">
                Gilang Pangestu
            </div>

        </div>

    </div>

    <!-- MENU SECTION -->
    <div>

        <div class="text-center mb-10">

            <h2 class="text-4xl font-bold text-[#1f1f1f]">
                Access what you need
            </h2>

            <p class="text-[#6b6b6b] mt-3 text-lg">
                Quick access to the tools and features you use most
            </p>

        </div>

        <!-- MENU GRID -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">

            <!-- CARD -->
            <a href="#"
                class="bg-white border border-[#ececec] rounded-3xl p-8 hover:shadow-md transition group text-center">

                <div
                    class="w-20 h-20 mx-auto rounded-full bg-[#f1f1f1] flex items-center justify-center mb-6 group-hover:scale-105 transition">

                    <!-- ICON -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8 text-[#1f1f1f]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                    </svg>

                </div>

                <p class="font-medium text-[#2c1f16]">
                    Bahan Baku
                </p>
            </a>

            <!-- MENU -->
            <a href="#"
                class="bg-white border border-[#ececec] rounded-3xl p-8 hover:shadow-md transition text-center">

                <div class="w-20 h-20 mx-auto rounded-full bg-[#f1f1f1] flex items-center justify-center mb-6">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v12m6-6H6" />
                    </svg>

                </div>

                <p class="font-medium text-[#2c1f16]">
                    Menu
                </p>
            </a>

            <!-- LAPORAN -->
            <a href="#"
                class="bg-white border border-[#ececec] rounded-3xl p-8 hover:shadow-md transition text-center">

                <div class="w-20 h-20 mx-auto rounded-full bg-[#f1f1f1] flex items-center justify-center mb-6">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 17v-6m4 6V7m4 10v-3" />
                    </svg>

                </div>

                <p class="font-medium text-[#2c1f16]">
                    Laporan
                </p>
            </a>

            <!-- STOK -->
            <a href="#"
                class="bg-white border border-[#ececec] rounded-3xl p-8 hover:shadow-md transition text-center">

                <div class="w-20 h-20 mx-auto rounded-full bg-[#f1f1f1] flex items-center justify-center mb-6">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 3h14M5 8h14M5 13h14M5 18h14" />
                    </svg>

                </div>

                <p class="font-medium text-[#2c1f16]">
                    Stok Jadi
                </p>
            </a>

            <!-- ARMADA -->
            <a href="#"
                class="bg-white border border-[#ececec] rounded-3xl p-8 hover:shadow-md transition text-center">

                <div class="w-20 h-20 mx-auto rounded-full bg-[#f1f1f1] flex items-center justify-center mb-6">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 17V5l12-2v12" />
                    </svg>

                </div>

                <p class="font-medium text-[#2c1f16]">
                    Armada
                </p>
            </a>

            <!-- PRODUKSI -->
            <a href="#"
                class="bg-white border border-[#ececec] rounded-3xl p-8 hover:shadow-md transition text-center">

                <div class="w-20 h-20 mx-auto rounded-full bg-[#f1f1f1] flex items-center justify-center mb-6">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3" />
                    </svg>

                </div>

                <p class="font-medium text-[#2c1f16]">
                    Produksi
                </p>
            </a>

        </div>

    </div>

</div>

@endsection