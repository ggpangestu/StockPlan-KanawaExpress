<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-[#573917]">Kanawa Express</h1>
        <p class="text-sm text-[#b87a2c]">Inventory System</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" class="text-[#573917]" />
            <x-text-input 
                id="username"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:border-[#f8a63c] focus:ring-[#f8a63c]"
                type="text"
                name="username"
                :value="old('username')"
                required autofocus
            />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-[#573917]" />
            <x-text-input 
                id="password"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:border-[#f8a63c] focus:ring-[#f8a63c]"
                type="password"
                name="password"
                required
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-[#573917]">
                <input 
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-[#f8a63c] focus:ring-[#f8a63c]"
                >
                Remember me
            </label>
        </div>

        <!-- Button -->
        <button type="submit"
            class="w-full bg-[#f8a63c] hover:bg-[#d98c36] active:bg-[#b87a2c] text-white font-semibold py-2.5 rounded-lg transition">
            Log in
        </button>
    </form>
</x-guest-layout>