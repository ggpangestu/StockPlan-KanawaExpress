@extends('layouts.app')

@section('content')
<div class="text-[#2c1f16] font-sans" translate="no">

    <div class="max-w-7xl mx-auto space-y-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#2c1f16] mb-1">Armada</h1>
                <p class="text-[#5c4432] text-sm">Kelola dan pantau seluruh armada pengiriman.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <button class="flex items-center gap-2 px-4 py-2 bg-white border border-[#e6dcd0] rounded-xl text-sm hover:bg-white/50 transition shadow-sm">
                    <svg class="w-4 h-4 text-[#5c4432]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Tag: Semua
                    <svg class="w-4 h-4 text-[#8b6f56]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#8b6f56]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" placeholder="Cari cepat..." class="pl-9 pr-4 py-2 w-full md:w-64 bg-white border border-[#e6dcd0] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#5C3D2E] shadow-sm">
                </div>

                <a href="{{ route('owner.armada.create') }}" class="bg-[#4A3219] hover:bg-[#382613] text-white px-5 py-2 rounded-xl text-sm font-medium transition shadow-sm flex items-center gap-2">
                    <span>+</span> Tambah armada
                </a>
            </div>
        </div>

        <div class="bg-white/60 backdrop-blur-sm border border-[#e6dcd0] rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4 font-semibold text-[#2c1f16]">
                <svg class="w-5 h-5 text-[#8b6f56]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                Armada Overview
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-5 rounded-xl border border-[#e6dcd0] flex items-center shadow-sm">
                    <div class="bg-[#f5efe6] p-4 rounded-full mr-4">
                        <svg class="w-8 h-8 text-[#5C3D2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <p class="text-sm text-[#5c4432] font-medium">Vehicle in Warehouse</p>
                            <span class="text-[11px] bg-[#f5efe6] text-[#5c4432] px-2 py-1 rounded-md font-medium">Currently inside</span>
                        </div>
                        <div class="flex items-baseline gap-1 mb-2">
                            <h3 class="text-3xl font-extrabold text-[#2c1f16]">
                                {{ $armadas->count() }}
                            </h3>
                            <span class="text-[#8b6f56] font-medium text-sm">
                                / {{ $armadas->count() }}
                            </span>
                        </div>
                        <div class="w-full bg-[#e6dcd0] rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: 10%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-[#e6dcd0] flex items-center shadow-sm">
                    <div class="bg-[#E4F0E6] p-4 rounded-full mr-4">
                        <svg class="w-8 h-8 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <p class="text-sm text-[#5c4432] font-medium">Products in Armada</p>
                            <span class="text-[11px] bg-[#EAF5EC] text-[#2E7D32] px-2 py-1 rounded-md font-medium">Awaiting sale</span>
                        </div>
                        <div class="flex items-baseline gap-1 mb-2">
                            <h3 class="text-3xl font-extrabold text-[#2c1f16]">67</h3>
                            <span class="text-[#8b6f56] font-medium text-sm">/ 90</span>
                        </div>
                        <div class="w-full bg-[#e6dcd0] rounded-full h-2">
                            <div class="bg-[#388E3C] h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($armadas as $armada)

                <div class="bg-white rounded-3xl border border-[#e8e8e5] shadow-sm p-6 hover:shadow-md transition">

                    <h3 class="text-2xl font-bold text-[#2f2f2f]">
                        {{ $armada->name }}
                    </h3>

                    <div class="mt-5 space-y-2">
                        <div class="text-[#5f5f5f]">
                            <span class="font-medium">Username:</span>
                            {{ $armada->username }}
                        </div>
                        <div class="text-[#5f5f5f]">
                            <span class="font-medium">ID:</span>
                            {{ $armada->id }}
                        </div>
                    </div>

                    <div class="mt-5">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                            {{ ucfirst($armada->role) }}
                        </span>
                    </div>

                </div>

            @empty

                <div class="col-span-full bg-white rounded-3xl border border-[#e8e8e5] shadow-sm p-16 text-center">
                    <div class="text-sm font-medium text-[#2f2f2f]">
                        Belum ada armada.
                    </div>
                    <p class="text-sm text-[#8a8a8a] mt-2">
                        Tambahkan armada pertama untuk memulai.
                    </p>
                </div>

            @endforelse

        </div>

    </div>
</div>
@endsection