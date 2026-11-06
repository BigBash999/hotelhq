<x-guest-layout>
    <div class="mb-8 text-center relative">
        <h2 class="text-3xl font-black text-white drop-shadow-md">Welcome Back</h2>
        <p class="text-sm text-gray-300 mt-2">Sign in to your staff account</p>
    </div>

    <x-auth-session-status class="mb-4 text-emerald-400 text-sm text-center font-medium" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5 relative">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
            <div style="position: relative;">
                <div style="position: absolute; top: 0; bottom: 0; left: 0; padding-left: 12px; display: flex; align-items: center; pointer-events: none;">
                    <svg style="height: 18px; width: 18px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="glass-input" placeholder="john@hotelhq.test">
            </div>
            @error('email') <span class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Password</label>
            <div style="position: relative;">
                <div style="position: absolute; top: 0; bottom: 0; left: 0; padding-left: 12px; display: flex; align-items: center; pointer-events: none;">
                    <svg style="height: 18px; width: 18px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="glass-input" placeholder="••••••••">
            </div>
            @error('password') <span class="text-red-400 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-6">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded bg-black/40 border-white/20 text-indigo-500 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-xs text-gray-400 hover:text-gray-200 transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-indigo-400 hover:text-indigo-300 font-medium transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="pt-4">
            <button type="submit" class="glass-btn">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Log In
            </button>
        </div>
    </form>
</x-guest-layout>