@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">
        Tambah Armada
    </h1>

    <form action="{{ route('owner.armada.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-2">Nama</label>
            <input
                type="text"
                name="name"
                class="w-full border rounded-lg p-3"
                required>
        </div>

        <div class="mb-4">
            <label class="block mb-2">Username</label>
            <input
                type="text"
                name="username"
                class="w-full border rounded-lg p-3"
                required>
        </div>

        <div class="mb-4">
            <label class="block mb-2">Password</label>
            <input
                type="password"
                name="password"
                class="w-full border rounded-lg p-3"
                required>
        </div>

        <button
            type="submit"
            class="bg-[#4A3219] text-white px-5 py-2 rounded-lg">
            Simpan
        </button>

    </form>

</div>

@endsection