<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-white">Set New Password</h2>
        <p class="text-sm text-gray-400 mt-2">Please enter your new password below.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required 
                   class="w-full bg-gray-900 border border-gray-600 rounded-lg block p-2.5 text-sm text-gray-500" readonly>
            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">New Password</label>
            <input id="password" type="password" name="password" required 
                   class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 text-sm" 
                   style="color: white !important; -webkit-text-fill-color: white !important;" 
                   placeholder="••••••••">
            @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required 
                   class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 text-sm" 
                   style="color: white !important; -webkit-text-fill-color: white !important;" 
                   placeholder="••••••••">
            @error('password_confirmation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="group w-full relative inline-flex items-center justify-center px-4 py-3 text-sm font-bold text-white transition-all duration-200 bg-indigo-600 border-2 border-indigo-400 rounded-lg hover:bg-indigo-500 hover:border-indigo-300 hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] hover:-translate-y-0.5">
                Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>