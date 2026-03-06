<x-guest-layout>
    <div class="animate-slide-up">
        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Selamat Datang Kembali</h2>
            <p class="text-slate-500 mt-1.5 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email Address --}}
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <div class="relative mt-1.5">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <x-text-input id="email" class="input input-with-icon" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between">
                    <x-input-label for="password" :value="__('Password')" />
                    @if (Route::has('password.request'))
                        <a class="text-xs font-medium text-primary-600 hover:text-primary-700 transition-colors" href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                </div>
                <div class="relative mt-1.5">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <x-text-input id="password" class="input input-with-icon" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center">
                <input id="remember_me" type="checkbox"
                       class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500/20 transition" name="remember">
                <label for="remember_me" class="ml-2.5 text-sm text-slate-600">{{ __('Ingat saya') }}</label>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn-primary w-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Masuk
            </button>
        </form>

        {{-- Divider --}}
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
            <div class="relative flex justify-center text-sm"><span class="px-3 bg-slate-50 text-slate-400">atau</span></div>
        </div>

        {{-- Register Link --}}
        <p class="text-center text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Daftar Sekarang</a>
        </p>
    </div>
</x-guest-layout>
