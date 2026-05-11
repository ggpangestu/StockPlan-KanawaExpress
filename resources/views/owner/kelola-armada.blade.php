@extends('layouts.app')

@section('content')
<div class="text-[#2c1f16] font-sans">
    
    <div class="w-full bg-white/50 backdrop-blur-sm border border-white/20 rounded-2xl mb-6 py-3 px-6 flex items-center gap-2 text-sm shadow-sm">
        <svg class="w-5 h-5 text-[#5c4432]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
        Bahan baku akan bertahan: <span class="font-bold">7 hari (Good)</span>
    </div>

    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
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
                <button class="bg-[#4A3219] hover:bg-[#382613] text-white px-5 py-2 rounded-xl text-sm font-medium transition shadow-sm flex items-center gap-2">
                    <span>+</span> Tambah armada
                </button>
            </div>
        </div>

        <div class="bg-white/60 backdrop-blur-sm border border-[#e6dcd0] rounded-2xl p-6 mb-8 shadow-sm">
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
                            <h3 class="text-3xl font-extrabold text-[#2c1f16]">0</h3>
                            <span class="text-[#8b6f56] font-medium text-sm">/ 3</span>
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white border border-[#e6dcd0] rounded-2xl p-6 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-bold text-[#2c1f16] text-lg">Armada 1</h3>
                    <span class="flex items-center gap-1.5 text-xs font-semibold text-[#2E7D32] bg-[#EAF5EC] px-2.5 py-1 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-[#2E7D32]"></span> Active
                    </span>
                </div>
                
                <div class="flex justify-center mb-10">
                    <div class="bg-[#f5efe6] p-6 rounded-full">
                        <svg class="w-16 h-16 text-[#2c1f16]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    </div>
                </div>

                <div class="border-t border-[#e6dcd0] pt-4 mt-auto">
                    <p class="text-xs text-[#8b6f56] mb-2">Driver</p>
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-[#f5efe6] text-[#5C3D2E] flex items-center justify-center text-xs font-bold">G</div>
                            <span class="text-sm font-semibold text-[#2c1f16]">Ghafi</span>
                        </div>
                        <span class="text-[10px] text-[#8b6f56]">Update terakhir: 3 menit lalu</span>
                    </div>

                    <div class="flex justify-between items-end mb-2">
                        <div class="flex items-center gap-1.5 text-[#5c4432] text-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Kapasitas
                        </div>
                        <span class="text-sm font-bold text-[#2c1f16]">60%</span>
                    </div>
                    <div class="w-full bg-[#e6dcd0] rounded-full h-2">
                        <div class="bg-[#FBC02D] h-2 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-[#e6dcd0] rounded-2xl p-6 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-bold text-[#2c1f16] text-lg">Armada 2</h3>
                    <span class="flex items-center gap-1.5 text-xs font-semibold text-[#2E7D32] bg-[#EAF5EC] px-2.5 py-1 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-[#2E7D32]"></span> Active
                    </span>
                </div>
                
                <div class="flex justify-center mb-10">
                    <div class="bg-[#f5efe6] p-6 rounded-full">
                        <svg class="w-16 h-16 text-[#2c1f16]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    </div>
                </div>

                <div class="border-t border-[#e6dcd0] pt-4 mt-auto">
                    <p class="text-xs text-[#8b6f56] mb-2">Driver</p>
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-[#f5efe6] text-[#5C3D2E] flex items-center justify-center text-xs font-bold">N</div>
                            <span class="text-sm font-semibold text-[#2c1f16]">Nail</span>
                        </div>
                        <span class="text-[10px] text-[#8b6f56]">Update terakhir: 3 menit lalu</span>
                    </div>

                    <div class="flex justify-between items-end mb-2">
                        <div class="flex items-center gap-1.5 text-[#5c4432] text-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Kapasitas
                        </div>
                        <span class="text-sm font-bold text-[#2c1f16]">15%</span>
                    </div>
                    <div class="w-full bg-[#e6dcd0] rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: 15%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-[#e6dcd0] rounded-2xl p-6 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-bold text-[#2c1f16] text-lg">Armada 3</h3>
                    <span class="flex items-center gap-1.5 text-xs font-semibold text-[#2E7D32] bg-[#EAF5EC] px-2.5 py-1 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-[#2E7D32]"></span> Active
                    </span>
                </div>
                
                <div class="flex justify-center mb-10">
                    <div class="bg-[#f5efe6] p-6 rounded-full">
                        <svg class="w-16 h-16 text-[#2c1f16]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    </div>
                </div>

                <div class="border-t border-[#e6dcd0] pt-4 mt-auto">
                    <p class="text-xs text-[#8b6f56] mb-2">Driver</p>
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-[#f5efe6] text-[#5C3D2E] flex items-center justify-center text-xs font-bold">G</div>
                            <span class="text-sm font-semibold text-[#2c1f16]">Gilang</span>
                        </div>
                        <span class="text-[10px] text-[#8b6f56]">Update terakhir: 3 menit lalu</span>
                    </div>

                    <div class="flex justify-between items-end mb-2">
                        <div class="flex items-center gap-1.5 text-[#5c4432] text-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Kapasitas
                        </div>
                        <span class="text-sm font-bold text-[#2c1f16]">80%</span>
                    </div>
                    <div class="w-full bg-[#e6dcd0] rounded-full h-2">
                        <div class="bg-[#388E3C] h-2 rounded-full" style="width: 80%"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection