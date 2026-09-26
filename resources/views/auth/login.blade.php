<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f5f5f2">

    <title>Sign in — StockPlan · Kanawa Express</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        input[type="password"]::-ms-reveal {
            display: none;
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden bg-[#f5f5f2] text-[#181818] antialiased">

    <div class="grid min-h-dvh lg:grid-cols-[minmax(0,1.08fr)_minmax(0,0.92fr)]">

        {{-- =========================================================
             DESKTOP BRAND PANEL
        ========================================================== --}}
        <section
            aria-label="Kanawa Express"
            class="relative hidden flex-col justify-between border-r border-[#deded9] bg-[#f5f5f2] px-10 py-8 lg:flex xl:px-16 xl:py-10"
        >

            {{-- TOP META --}}
            <div class="flex items-center justify-between text-[11px] font-medium uppercase tracking-[0.2em] text-[#777772]">
                <span>StockPlan</span>
                <span>Operations System</span>
            </div>


            {{-- BRAND / HERO --}}
            <div class="mx-auto flex w-full max-w-[620px] flex-1 flex-col justify-center">

                {{-- MOBILE UNIT VISUAL --}}
                <div class="mx-auto mt-2 w-full max-w-[540px]">
                    <div class="relative h-[clamp(320px,44vh,440px)] w-full">
                        <img
                            src="{{ asset('images/design_sticker_payung_kanawa_express_bg.png') }}"
                            alt="Kanawa Express mobile unit visual"
                            class="absolute inset-0 h-full w-full object-contain object-center mix-blend-multiply"
                        >
                    </div>
                </div>


                {{-- BRAND MESSAGE --}}
                <div class="mt-32 flex items-end justify-between gap-8">
                    <div>
                        <p class="text-4xl font-medium tracking-tight text-[#181818] xl:text-5xl">
                            Built to move.
                        </p>

                        <p class="mt-3 max-w-sm text-sm leading-relaxed text-[#6f6f6a]">
                            Stock, units, and daily operations for every Kanawa Express mobile outlet.
                        </p>
                    </div>
                </div>

            </div>

        </section>


        {{-- =========================================================
             LOGIN PANEL
        ========================================================== --}}
        <main class="flex min-h-dvh flex-col px-5 py-8 sm:px-8 lg:px-12 lg:py-10">

            <div class="mx-auto flex w-full max-w-[420px] flex-1 flex-col justify-center">

                {{-- MOBILE LOGO --}}
                <div class="mb-8 lg:hidden">
                    <div class="mx-auto w-full max-w-[280px]">
                        <img
                            src="{{ asset('images/logo.png') }}"
                            alt="Kanawa Express"
                            class="block h-auto w-full"
                        >
                    </div>
                </div>


                {{-- LOGIN CARD --}}
                <div class="rounded-[24px] border border-[#deded9] bg-white p-6 shadow-[0_1px_2px_rgba(24,24,24,0.04),0_16px_40px_-18px_rgba(24,24,24,0.14)] sm:p-9">

                    {{-- HEADER --}}
                    <header class="mb-7">
                        <h1 class="text-2xl font-semibold tracking-tight text-[#181818] sm:text-[28px]">
                            Welcome back
                        </h1>

                        <p class="mt-2 text-sm leading-relaxed text-[#6f6f6a]">
                            Sign in to continue your daily operation.
                        </p>
                    </header>


                    {{-- SESSION STATUS --}}
                    <x-auth-session-status
                        class="mb-5 text-sm font-medium text-emerald-700"
                        :status="session('status')"
                    />


                    {{-- LOGIN ERROR --}}
                    @if ($errors->any() && !$lockSeconds)
                        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3">
                            <div class="flex items-start gap-3">

                                <i
                                    data-lucide="circle-alert"
                                    class="mt-0.5 size-5 shrink-0 text-red-600"
                                    aria-hidden="true"
                                ></i>

                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-red-800">
                                        Login failed
                                    </p>

                                    <p class="mt-1 text-sm leading-relaxed text-red-700">
                                        {{ $errors->first() }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    @endif


                    {{-- FORM --}}
                    <form
                        method="POST"
                        action="{{ route('login') }}"
                        class="flex flex-col gap-5"
                        x-data="{
                            loading: false,
                            showPassword: false,
                            locked: @js($lockSeconds > 0),
                            seconds: @js((int) $lockSeconds),
                            timer: null,

                            init() {
                                if (!this.locked || this.seconds <= 0) {
                                    return;
                                }

                                this.timer = setInterval(() => {
                                    if (this.seconds > 1) {
                                        this.seconds--;
                                        return;
                                    }

                                    this.seconds = 0;
                                    this.locked = false;

                                    clearInterval(this.timer);
                                    this.timer = null;
                                }, 1000);
                            }
                        }"
                        @submit="
                            if (locked) {
                                $event.preventDefault();
                                return;
                            }

                            loading = true;
                        "
                    >
                        @csrf


                        {{-- USERNAME --}}
                        <div class="flex flex-col gap-2">
                            <label
                                for="username"
                                class="text-sm font-medium text-[#181818]"
                            >
                                Username
                            </label>

                            <div class="group relative">

                                <i
                                    data-lucide="user-round"
                                    class="pointer-events-none absolute left-4 top-1/2 size-[18px] -translate-y-1/2 text-[#85857f] transition-colors group-focus-within:text-[#181818]"
                                    aria-hidden="true"
                                ></i>

                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    autocapitalize="none"
                                    spellcheck="false"
                                    placeholder="Enter your username"
                                    class="h-12 w-full rounded-xl border border-[#d9d9d4] bg-white pl-11 pr-4 text-[15px] text-[#181818] outline-none transition-[border-color,box-shadow] placeholder:text-[#999993] hover:border-[#c6c6c0] focus:border-[#181818] focus:ring-4 focus:ring-[#181818]/[0.06]"
                                >

                            </div>
                        </div>


                        {{-- PASSWORD --}}
                        <div class="flex flex-col gap-2">
                            <label
                                for="password"
                                class="text-sm font-medium text-[#181818]"
                            >
                                Password
                            </label>

                            <div class="group relative">

                                <i
                                    data-lucide="lock-keyhole"
                                    class="pointer-events-none absolute left-4 top-1/2 size-[18px] -translate-y-1/2 text-[#85857f] transition-colors group-focus-within:text-[#181818]"
                                    aria-hidden="true"
                                ></i>

                                <input
                                    id="password"
                                    name="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    class="h-12 w-full rounded-xl border border-[#d9d9d4] bg-white pl-11 pr-12 text-[15px] text-[#181818] outline-none transition-[border-color,box-shadow] placeholder:text-[#999993] hover:border-[#c6c6c0] focus:border-[#181818] focus:ring-4 focus:ring-[#181818]/[0.06]"
                                >

                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                    :aria-pressed="showPassword"
                                    class="absolute right-2 top-1/2 flex size-9 -translate-y-1/2 items-center justify-center rounded-lg text-[#85857f] transition-colors hover:bg-[#eeece8] hover:text-[#181818] focus:outline-none focus:ring-2 focus:ring-[#181818]/10"
                                >
                                    <i
                                        x-show="!showPassword"
                                        data-lucide="eye"
                                        class="size-[18px]"
                                        aria-hidden="true"
                                    ></i>

                                    <i
                                        x-show="showPassword"
                                        data-lucide="eye-off"
                                        class="size-[18px]"
                                        aria-hidden="true"
                                    ></i>
                                </button>

                            </div>
                        </div>


                        {{-- REMEMBER ME --}}
                        <label class="flex w-fit cursor-pointer select-none items-center gap-2.5 text-sm text-[#6f6f6a]">
                            <input
                                type="checkbox"
                                name="remember"
                                class="size-4 cursor-pointer rounded border-[#d2d2cd] accent-[#181818] focus:ring-[#181818]"
                            >

                            Remember me
                        </label>


                        {{-- SUBMIT --}}
                        <button
                            type="submit"
                            x-bind:disabled="loading || locked"
                            class="group mt-1 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#181818] text-[15px] font-medium text-white transition-colors hover:bg-[#2a2a2a] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#181818] disabled:cursor-not-allowed disabled:opacity-50"
                        >

                            {{-- LOADING SPINNER --}}
                            <svg
                                x-show="loading"
                                x-cloak
                                class="size-4 animate-spin"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
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
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                ></path>
                            </svg>


                            {{-- BUTTON TEXT --}}
                            <span
                                x-text="
                                    loading
                                        ? 'Signing in…'
                                        : locked
                                            ? `Try again in ${seconds}s`
                                            : 'Sign In'
                                "
                            ></span>


                            {{-- ARROW --}}
                            <i
                                x-show="!loading && !locked"
                                data-lucide="arrow-right"
                                class="size-4 transition-transform group-hover:translate-x-0.5"
                                aria-hidden="true"
                            ></i>

                        </button>

                    </form>

                </div>


                {{-- AUTHORIZATION NOTICE --}}
                <p class="mt-6 text-center text-xs text-[#777772]">
                    Authorized Kanawa Express personnel only.
                </p>

            </div>


            {{-- FOOTER --}}
            <footer class="mx-auto mt-8 flex w-full max-w-[420px] items-center justify-between text-xs text-[#85857f]">
                <span>StockPlan</span>
                <span>&copy; {{ date('Y') }} Kanawa Express</span>
            </footer>

        </main>

    </div>

</body>
</html>