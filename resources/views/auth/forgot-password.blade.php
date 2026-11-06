<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-white">Forgot Password?</h2>
        <p class="text-sm text-gray-400 mt-2">
            No problem. Just let us know your email address and we will email you a password reset link.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-emerald-400 text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
            <input id="email" type="email" name="email" required autofocus 
                   class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 text-sm" 
                   style="color: white !important; -webkit-text-fill-color: white !important;" 
                   placeholder="john@hotelhq.test">
            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="group w-full relative inline-flex items-center justify-center px-4 py-3 text-sm font-bold text-white transition-all duration-200 bg-indigo-600 border-2 border-indigo-400 rounded-lg hover:bg-indigo-500 hover:border-indigo-300 hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] hover:-translate-y-0.5">
                Email Password Reset Link
            </button>
        </div>

        <!-- Back to Login -->
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-white transition flex items-center justify-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to login
            </a>
        </div>
    </form>
</x-guest-layout>