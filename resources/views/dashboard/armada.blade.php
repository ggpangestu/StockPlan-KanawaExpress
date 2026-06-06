@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-3xl font-bold text-[#2c1f16]">
            Dashboard armada
        </h1>

        <p class="text-[#5c4432] mt-1">
            Ringkasan operasional Kanawa Express.
        </p>
    </div>

    <a href="{{ route('armada.sessions.index') }}" class="block rounded-3xl bg-white p-6 shadow-sm border border-black/5 hover:border-[#2f2f2f] transition">

        <h2 class="text-lg font-semibold text-[#2c1f16]">
            Stok & Penjualan
        </h2>

        <p class="text-sm mt-2 text-[#5c4432]">
            Catat produk terjual dan selesaikan sesi penjualan.
        </p>

    </a>

</div>

@endsection
