@extends('layouts.app')

@section('content')
<style>
    .card-custom { background: white; border: 1px solid #ece7e2; border-radius: 28px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,.03); transition: all 0.3s ease; }
    .card-custom:hover { border-color: #d8caca; box-shadow: 0 10px 30px rgba(44, 31, 22, 0.08); transform: translateY(-3px); }
    .icon-box-soft { width: 48px; height: 48px; border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .menu-card { background: white; border: 1px solid #ece7e2; border-radius: 28px; padding: 32px 24px; text-align: center; transition: all 0.3s ease; position: relative; overflow: hidden; }
    .menu-card:hover { border-color: #2c1f16; box-shadow: 0 12px 30px rgba(44, 31, 22, 0.08); transform: translateY(-5px); }
    .menu-icon-wrapper { width: 72px; height: 72px; border-radius: 22px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; transition: all 0.3s ease; }
    .menu-card:hover .menu-icon-wrapper { transform: scale(1.1) rotate(-5deg); }
    .bg-pattern { background-image: radial-gradient(#2c1f16 1px, transparent 1px); background-size: 24px 24px; opacity: 0.03; }
</style>

<div class="space-y-8 font-sans pb-10">

    <div class="relative bg-[#2c1f16] rounded-[32px] p-8 md:p-12 overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl shadow-[#2c1f16]/20">
        <div class="absolute inset-0 bg-pattern"></div>
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-[#ff6a3d] rounded-full blur-[80px] opacity-20"></div>
        <div class="absolute right-40 -bottom-20 w-64 h-64 bg-[#f8f5f2] rounded-full blur-[80px] opacity-10"></div>

        <div class="relative z-10 max-w-2xl text-center md:text-left">
            
            <h1 class="text-4xl md:text-5xl font-black text-white leading-tight mb-4 tracking-tight">
                Selamat Datang, <span class="text-[#ff6a3d]">{{ Auth::user()->name ?? 'Owner' }}</span>!
            </h1>
            
            <p class="text-white/70 text-lg md:text-xl leading-relaxed font-medium mb-8">
                Pantau seluruh operasional bisnis Anda hari ini. Mulai dari manajemen bahan baku di gudang, antrean dapur, hingga distribusi armada keliling.
            </p>
        </div>

        <div class="relative z-10 hidden lg:block">
            <div class="w-48 h-48 rounded-full border-[8px] border-white/10 flex items-center justify-center bg-white/5 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-white/90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card-custom !p-6 flex items-center gap-5">
            <div class="icon-box-soft bg-blue-50 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tiket Produksi Aktif</p>
                <h4 class="text-2xl font-black text-[#2c1f16]">{{ \App\Models\Production::whereIn('status', ['planned', 'processing'])->count() ?? 0 }}</h4>
            </div>
        </div>
        
        <div class="card-custom !p-6 flex items-center gap-5">
            <div class="icon-box-soft bg-green-50 text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Menu</p>
                <h4 class="text-2xl font-black text-[#2c1f16]">{{ \App\Models\Menu::count() ?? 0 }}</h4>
            </div>
        </div>

        <div class="card-custom !p-6 flex items-center gap-5">
            <div class="icon-box-soft bg-orange-50 text-orange-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 17 6-6-6-6"/><path d="M19 11v6a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Stok Bahan Baku</p>
                <h4 class="text-2xl font-black text-[#2c1f16]">{{ \App\Models\RawMaterial::count() ?? 0 }}</h4>
            </div>
        </div>

        <div class="card-custom !p-6 flex items-center gap-5">
            <div class="icon-box-soft bg-purple-50 text-purple-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1 .4-1 1v10c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Armada</p>
                <h4 class="text-2xl font-black text-[#2c1f16]">{{ \App\Models\User::where('role', 'armada')->count() ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <hr class="border-gray-200">

    <div>
        <div class="mb-8">
            <h2 class="text-2xl font-black text-[#2c1f16] tracking-tight">Pusat Kendali Aplikasi</h2>
            <p class="text-[#5c4432] font-medium mt-1">Akses cepat ke seluruh modul manajemen utama Kanawa Express.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
            
            <a href="{{ route('owner.raw-materials.index') }}" class="menu-card group">
                <div class="menu-icon-wrapper bg-[#fff5ec] text-[#ff8c3a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                </div>
                <h3 class="font-black text-[#2c1f16] mb-1">Bahan Baku</h3>
                <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">Gudang Mentah</p>
            </a>

            <a href="{{ route('owner.menus.index') }}" class="menu-card group">
                <div class="menu-icon-wrapper bg-[#f3f0ff] text-[#7c3aed]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3 class="font-black text-[#2c1f16] mb-1">Daftar Menu</h3>
                <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">Katalog & Resep</p>
            </a>

            <a href="{{ route('owner.productions.index') }}" class="menu-card group">
                <div class="menu-icon-wrapper bg-[#e8f5e9] text-[#2e7d32]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                </div>
                <h3 class="font-black text-[#2c1f16] mb-1">Produksi</h3>
                <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">Antrean Dapur</p>
            </a>

            <a href="{{ route('owner.stok-jadi.index') }}" class="menu-card group">
                <div class="menu-icon-wrapper bg-[#e3f2fd] text-[#1976d2]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
                </div>
                <h3 class="font-black text-[#2c1f16] mb-1">Stok Jadi</h3>
                <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">Etalase Barang</p>
            </a>

            <a href="{{ route('owner.armada-sessions.index') }}" class="menu-card group">
                <div class="menu-icon-wrapper bg-[#fff0f5] text-[#db2777]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1 .4-1 1v10c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                </div>
                <h3 class="font-black text-[#2c1f16] mb-1">Sesi Armada</h3>
                <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">Distribusi Kurir</p>
            </a>

            <a href="{{ route('owner.reports.transactions') }}" class="menu-card group">
                <div class="menu-icon-wrapper bg-[#f1f5f9] text-[#475569]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </div>
                <h3 class="font-black text-[#2c1f16] mb-1">Laporan</h3>
                <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">Analisis & HPP</p>
            </a>

        </div>
    </div>

</div>
@endsection