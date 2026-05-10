<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-black text-white overflow-x-hidden">
    @php
        $lockSeconds = null;

            if ($errors->has('username')) {
                preg_match('/([0-9]+)\sseconds/', $errors->first('username'), $matches);

                if (isset($matches[1])) {
                    $lockSeconds = (int) $matches[1];
                }
            }
    @endphp

    <!-- BACKGROUND -->
    <div class="fixed inset-0">
        <img 
            src="{{ asset('images/bg_login.webp') }}"
            alt="Background"
            class="w-full h-full object-cover"
        >

        <!-- DARK OVERLAY -->
        <div class="absolute inset-0 bg-black/50"></div>

        <!-- SOFT GLOW -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(202,158,118,0.18),transparent_35%)]"></div>
    </div>

    <!-- MAIN -->
    <div class="relative z-10 min-h-screen flex items-center">

        <!-- DESKTOP -->
        <div class="hidden lg:grid lg:grid-cols-2 w-full h-screen">

            <!-- LEFT SIDE -->
            <div class="flex flex-col items-center justify-center px-10 xl:px-20 2xl:px-32">

                <!-- LOGO -->
                <img 
                    src="{{ asset('images/logo_putih.png') }}"
                    alt="Logo"
                    class="w-[260px] xl:w-[340px] 2xl:w-[420px] max-w-full"
                >

                <!-- TAGLINE -->
                <div class="mt-6 max-w-md text-center">
                    <h2 class="text-base xl:text-lg 2xl:text-xl leading-relaxed text-[#CC9D7E]">
                        Inventory management made simple.
                    </h2>

                    <p class="text-base xl:text-lg 2xl:text-xl text-[#CC9D7E] leading-relaxed">
                        More time for what matters.
                    </p>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="flex items-center justify-center px-10">

                <!-- LOGIN CARD -->
                <div class="w-full max-w-[480px] rounded-[32px]
                            border border-[#473E37]
                            bg-[#16130F]/95
                            backdrop-blur-xl
                            p-10 shadow-2xl
                            transition-all duration-300">

                    <!-- TITLE -->
                    <div class="text-center">
                        <h1 class="text-4xl mt-6 text-[#ffffff]">
                            Welcome Back
                        </h1>

                        <p class="mt-2 text-base text-[#CC9D7E]/70 leading-relaxed">
                            Sign in to continue to your inventory management dashboard.
                        </p>
                    </div>

                    <!-- SESSION STATUS -->
                    <x-auth-session-status 
                        class="mt-6 text-sm text-center text-green-400" 
                        :status="session('status')" 
                    />

                    @if ($lockSeconds)

                        <div
                            x-data="{
                                show:true,
                                seconds: {{ $lockSeconds }},

                                startCountdown() {

                                    const timer = setInterval(() => {

                                        if (this.seconds > 1) {

                                            this.seconds--;

                                        } else {

                                            this.seconds = 0;
                                            this.show = false;

                                            clearInterval(timer);

                                        }

                                    }, 1000);
                                }
                            }"
                            x-init="startCountdown()"
                            x-show="show"
                            x-transition.opacity.duration.400ms
                            class="mt-6 rounded-2xl
                                border border-amber-500/20
                                bg-amber-500/10
                                px-5 py-4"
                        >

                            <div class="flex items-start gap-3">

                                <!-- ICON -->
                                <div class="text-amber-300 mt-[2px]">
                                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                                </div>

                                <!-- CONTENT -->
                                <div>

                                    <p class="text-sm font-medium text-amber-200">
                                        Too many login attempts
                                    </p>

                                    <p class="mt-1 text-sm text-amber-100/80">
                                        Please wait
                                        <span
                                            x-text="seconds"
                                            class="font-semibold text-amber-200"
                                        ></span>
                                        seconds before trying again.
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif

                    <!-- FORM -->
                    <form method="POST"
                        action="{{ route('login') }}"
                        class="mt-10 space-y-6"
                        x-data="{ loading:false }"
                        @submit="loading = true">

                        @csrf

                        <!-- USERNAME -->
                        <div>

                            <label class="block mb-3 text-sm text-[#F5EEE6]">
                                Username
                            </label>

                            <div class="relative group">

                                <!-- ICON -->
                                <div class="absolute inset-y-0 left-0
                                    flex items-center justify-center
                                    w-14 text-[#6B625B]
                                    transition-colors duration-200
                                    group-focus-within:text-[#CA9E76]">

                                    <i data-lucide="user" class="w-5 h-5"></i>

                                </div>

                                <input
                                    type="text"
                                    name="username"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus
                                    placeholder="Enter your username"
                                    class="w-full h-14 rounded-2xl
                                        border border-[#473E37]
                                        hover:border-[#5A4E45]
                                        bg-[#1B1713]
                                        pl-14 pr-5 text-[#F5EEE6]
                                        placeholder:text-[#6B625B]
                                        focus:outline-none
                                        focus:border-[#CA9E76]
                                        focus:ring-2 focus:ring-[#CA9E76]/20
                                        transition"
                                >

                            </div>

                            @if ($errors->has('username') && !$lockSeconds)

                                <div
                                    x-data="{ show:true }"
                                    x-show="show"
                                    x-transition.opacity.duration.300ms
                                    class="mt-3 flex items-start gap-3 rounded-2xl
                                        border border-red-500/20
                                        bg-red-500/10
                                        px-4 py-3"
                                >

                                    <!-- ICON -->
                                    <div class="mt-[2px] text-red-400">
                                        <i data-lucide="circle-alert" class="w-5 h-5"></i>
                                    </div>

                                    <!-- TEXT -->
                                    <div class="flex-1">

                                        <p class="text-sm font-medium text-red-300">
                                            Login Failed
                                        </p>

                                        <p class="mt-1 text-sm text-red-200/80">
                                            {{ $errors->first('username') }}
                                        </p>

                                    </div>

                                </div>

                            @endif

                        </div>

                        <!-- PASSWORD -->
                        <div
                            x-data="{ show:false }"
                            x-effect="$nextTick(() => createIcons({ icons }))"
                        >
                            <label class="block mb-3 text-sm text-[#F5EEE6]">
                                Password
                            </label>

                            <div class="relative group">

                                <!-- ICON -->
                                <div class="absolute inset-y-0 left-0
                                    flex items-center justify-center
                                    w-14 text-[#6B625B]
                                    transition-colors duration-200
                                    group-focus-within:text-[#CA9E76]">

                                    <i data-lucide="lock-keyhole" class="w-5 h-5"></i>

                                </div>

                                <input
                                    x-bind:type="show ? 'text' : 'password'"
                                    name="password"
                                    required
                                    placeholder="Enter your password"
                                    class="w-full h-14 rounded-2xl
                                        border border-[#473E37]
                                        hover:border-[#5A4E45]
                                        bg-[#1B1713]
                                        pl-14 pr-14 text-[#F5EEE6]
                                        placeholder:text-[#6B625B]
                                        focus:outline-none
                                        focus:border-[#CA9E76]
                                        focus:ring-2 focus:ring-[#CA9E76]/20
                                        transition"
                                >

                                <!-- TOGGLE -->
                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0
                                        flex items-center justify-center
                                        w-14 text-[#6B625B]
                                        hover:text-[#CA9E76]
                                        transition"
                                >

                                    <i
                                        x-show="!show"
                                        data-lucide="eye"
                                        class="w-5 h-5 absolute"
                                    ></i>

                                    <i
                                        x-show="show"
                                        data-lucide="eye-off"
                                        class="w-5 h-5 absolute"
                                    ></i>

                                </button>
                            </div>

                        </div>

                        <!-- REMEMBER -->
                        <div class="flex items-center justify-between">

                            <label class="flex items-center gap-3 text-sm text-[#CC9D7E]/80">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-[#473E37]
                                           bg-[#1B1713]
                                           text-[#CA9E76]
                                           focus:ring-[#CA9E76]"
                                >

                                Remember me
                            </label>

                        </div>

                        <!-- BUTTON -->
                        <button
                            type="submit"
                            x-bind:disabled="loading"
                            class="w-full h-14 rounded-2xl
                                bg-[#CA9E76]
                                text-[#16130F]
                                font-semibold text-lg
                                hover:brightness-110
                                hover:shadow-lg
                                active:scale-[0.99]
                                transition-all duration-200 ease-out
                                disabled:opacity-70
                                disabled:cursor-not-allowed
                                flex items-center justify-center gap-3"
                        >

                            <!-- SPINNER -->
                            <svg
                                x-show="loading"
                                class="w-5 h-5 animate-spin"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4
                                    a4 4 0 00-4 4H4z"
                                ></path>
                            </svg>

                            <!-- TEXT -->
                            <span x-text="loading ? 'Signing In...' : 'Sign In'"></span>

                        </button>

                    </form>

                    <div class="text-center">
                        <div class="flex mt-10 items-center gap-3">
    
                            <div class="h-px flex-1 bg-[#473E37]"></div>

                                <img 
                                    src="{{ asset('icons/coffee-bean.svg') }}"
                                    alt="Bean"
                                    class="w-7 h-7 opacity-90"
                                >

                            <div class="h-px flex-1 bg-[#473E37]"></div>

                        </div>

                        <p class="mt-2 text-sm text-[#CC9D7E]/70 leading-relaxed">
                            Good Coffee. Smart Inventory.
                        </p>
                    </div>

                </div>

            </div>

        </div>

        <!-- MOBILE -->
        <div class="lg:hidden relative w-full min-h-screen overflow-y-auto flex items-center justify-center p-6">

            <!-- MOBILE OVERLAY -->
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

            <!-- CARD -->
            <div class="relative z-10 w-full max-w-md my-6 rounded-[28px]
                        border border-[#473E37]
                        bg-[#16130F]/95
                        backdrop-blur-xl
                        p-7 shadow-2xl
                        transition-all duration-300">

                <!-- LOGO -->
                <div class="flex justify-center">
                    <img 
                        src="{{ asset('images/logo_putih.png') }}"
                        alt="Logo"
                        class="w-36 sm:w-40"
                    >
                </div>

                <!-- TITLE -->
                <div class="mt-8 text-center">
                    <h1 class="text-3xl font-semibold text-[#ffffff]">
                        Welcome Back
                    </h1>

                    <p class="mt-3 text-sm text-[#CC9D7E]/70">
                        Sign in to continue your dashboard.
                    </p>
                </div>

                <!-- SESSION STATUS -->
                <x-auth-session-status 
                    class="mt-6 text-sm text-center text-green-400" 
                    :status="session('status')" 
                />

                @if ($lockSeconds)

                    <div
                        x-data="{
                            show:true,
                            seconds: {{ $lockSeconds }},

                            startCountdown() {

                                const timer = setInterval(() => {

                                    if (this.seconds > 1) {

                                        this.seconds--;

                                    } else {

                                        this.seconds = 0;
                                        this.show = false;

                                        clearInterval(timer);

                                    }

                                }, 1000);
                            }
                        }"
                        x-init="startCountdown()"
                        x-show="show"
                        x-transition.opacity.duration.400ms
                            class="mt-6 rounded-2xl
                            border border-amber-500/20
                            bg-amber-500/10
                            px-5 py-4"
                    >

                        <div class="flex items-start gap-3">

                            <!-- ICON -->
                            <div class="text-amber-300 mt-[2px]">
                                <i data-lucide="shield-alert" class="w-5 h-5"></i>
                            </div>

                            <!-- CONTENT -->
                            <div>

                                <p class="text-sm font-medium text-amber-200">
                                    Too many login attempts
                                </p>

                                <p class="mt-1 text-sm text-amber-100/80">
                                    Please wait
                                    <span
                                        x-text="seconds"
                                        class="font-semibold text-amber-200"
                                    ></span>
                                    seconds before trying again.
                                </p>

                            </div>

                        </div>

                    </div>

                @endif

                <!-- FORM -->
                <form method="POST"
                    action="{{ route('login') }}"
                    class="mt-8 space-y-5"
                    x-data="{ loading:false }"
                    @submit="loading = true">

                    @csrf

                    <!-- USERNAME -->
                    <div>

                        <label class="block mb-3 text-sm text-[#F5EEE6]">
                            Username
                        </label>

                        <div class="relative group">

                            <!-- ICON -->
                            <div class="absolute inset-y-0 left-0
                                flex items-center justify-center
                                w-12 text-[#6B625B]
                                transition-colors duration-200
                                group-focus-within:text-[#CA9E76]">

                                <i data-lucide="user" class="w-5 h-5"></i>

                            </div>

                            <input
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                placeholder="Enter your username"
                                class="w-full h-14 rounded-2xl
                                    border border-[#473E37]
                                    hover:border-[#5A4E45]
                                    bg-[#1B1713]
                                    pl-14 pr-5 text-[#F5EEE6]
                                    placeholder:text-[#6B625B]
                                    focus:outline-none
                                    focus:border-[#CA9E76]
                                    focus:ring-2 focus:ring-[#CA9E76]/20
                                    transition"
                            >

                        </div>

                        @if ($errors->has('username') && !$lockSeconds)

                            <div
                                x-data="{ show:true }"
                                x-show="show"
                                x-transition.opacity.duration.300ms
                                class="mt-3 flex items-start gap-3 rounded-2xl
                                    border border-red-500/20
                                    bg-red-500/10
                                    px-4 py-3"
                            >

                                <!-- ICON -->
                                <div class="mt-[2px] text-red-400">
                                    <i data-lucide="circle-alert" class="w-5 h-5"></i>
                                </div>

                                <!-- TEXT -->
                                <div class="flex-1">

                                    <p class="text-sm font-medium text-red-300">
                                        Login Failed
                                    </p>

                                    <p class="mt-1 text-sm text-red-200/80">
                                        {{ $errors->first('username') }}
                                    </p>

                                </div>

                            </div>

                        @endif

                    </div>

                    <!-- PASSWORD -->
                    <div
                        x-data="{ show:false }"
                        x-effect="$nextTick(() => createIcons({ icons }))"
                    >

                        <label class="block mb-2 text-sm text-[#F5EEE6]">
                            Password
                        </label>

                        <div class="relative group">

                        <div class="absolute inset-y-0 left-0
                            flex items-center justify-center
                            w-12 text-[#6B625B]
                            transition-colors duration-200
                            group-focus-within:text-[#CA9E76]">

                            <i data-lucide="lock-keyhole" class="w-5 h-5"></i>

                        </div>

                        <input
                            x-bind:type="show ? 'text' : 'password'"
                            name="password"
                            required
                            placeholder="Enter your password"
                            class="w-full h-12 rounded-xl
                                border border-[#473E37]
                                hover:border-[#5A4E45]
                                bg-[#1B1713]
                                pl-12 pr-12 text-[#F5EEE6]
                                placeholder:text-[#6B625B]
                                focus:outline-none
                                focus:border-[#CA9E76]
                                focus:ring-2 focus:ring-[#CA9E76]/20
                                transition"
                        >

                            <!-- TOGGLE -->
                            <button
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0
                                    flex items-center justify-center
                                    w-12 text-[#6B625B]
                                    hover:text-[#CA9E76]
                                    transition"
                            >

                                <i
                                    x-show="!show"
                                    data-lucide="eye"
                                    class="w-5 h-5 absolute"
                                ></i>

                                <i
                                    x-show="show"
                                    data-lucide="eye-off"
                                    class="w-5 h-5 absolute"
                                ></i>

                            </button>

                        </div>

                    </div>

                    <!-- REMEMBER -->
                    <label class="flex items-center gap-3 text-sm text-[#CC9D7E]/80">
                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded border-[#473E37]
                            bg-[#1B1713]
                            text-[#CA9E76]"
                        >Remember me
                    </label>

                    <!-- BUTTON -->
                    <button
                        type="submit"
                        x-bind:disabled="loading"
                        class="w-full h-12 rounded-2xl
                            bg-[#CA9E76]
                            text-[#16130F]
                            font-semibold text-lg
                            hover:brightness-110
                            hover:shadow-lg
                            active:scale-[0.99]
                            transition-all duration-200 ease-out
                            disabled:opacity-70
                            disabled:cursor-not-allowed
                            flex items-center justify-center gap-3"
                    >

                    <!-- SPINNER -->
                        <svg
                            x-show="loading"
                            class="w-5 h-5 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4
                                a4 4 0 00-4 4H4z"
                            ></path>
                        </svg>

                        <!-- TEXT -->
                        <span x-text="loading ? 'Signing In...' : 'Sign In'"></span>

                    </button>

                </form>

            </div>

        </div>

    </div>

</body>
</html>