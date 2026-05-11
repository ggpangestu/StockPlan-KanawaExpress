@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F9F7F2] font-sans text-gray-900">
    <main class="flex-1 p-8">

        {{-- Info bar --}}
        <div class="flex items-center gap-2 text-gray-500 text-xs mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9a3412" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Bahan baku akan bertahan: <span class="font-bold">7 hari (Good)</span></span>
        </div>

        {{-- Separator --}}
        <hr class="border-gray-200 mb-8">

        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <h2 class="text-4xl font-black">Production</h2>

                <div class="flex gap-2">
                    <div class="relative">
                        <select class="appearance-none bg-transparent border-2 border-gray-300 rounded-full pl-6 pr-10 py-1.5 text-sm font-medium text-gray-500 focus:outline-none focus:border-black">
                            <option>Tag: Tag</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="relative ml-2">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Quick Search" class="pl-11 pr-6 py-1.5 border-2 border-gray-300 rounded-full w-64 focus:outline-none focus:border-black bg-transparent">
                </div>
            </div>

            {{-- Encapsulated Add Production button --}}
            <button class="flex items-center gap-2 bg-gray-900 text-white text-sm font-bold px-5 py-2.5 rounded-full hover:bg-gray-700 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/><path d="M12 5v14"/>
                </svg>
                Add Production
            </button>
        </div>

        <div class="space-y-6">
            @foreach(['002' => 'WORK IN PROGRESS', '001' => 'DONE'] as $id => $status)
                {{-- We wrap the card in a link --}}
                <a href="{{ route('production.show', $id) }}" class="block group">
                    <div class="bg-white border border-gray-200 rounded-3xl p-10 flex items-center justify-between shadow-sm transition hover:border-black hover:shadow-md">
                        <div>
                            <h3 class="text-3xl font-black mb-4 group-hover:text-blue-600 transition">Production #{{ $id }}</h3>
                            
                            {{-- Status pill code remains the same --}}
                            @if($status === 'DONE')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-700 border border-green-200">
                                    DONE
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-600 border border-blue-200">
                                    WORK IN PROGRESS
                                </span>
                            @endif
                        </div>

                        <div class="flex gap-3">
                            {{-- Detail Arrow (Optional UX addition) --}}
                            <div class="text-gray-300 group-hover:text-black transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </main>
</div>
@endsection