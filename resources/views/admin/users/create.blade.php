<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('User Management') }}
            </h2>
            <a href="{{ url()->previous() }}" class="group flex items-center gap-2 bg-gray-800 hover:bg-gray-700 border border-gray-600 text-gray-300 hover:text-white px-3 py-1.5 rounded-lg transition-all duration-200 shadow-sm text-sm font-medium">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </x-slot>

    <!-- Centered, compact layout -->
    <div class="py-6 bg-gray-900 min-h-[calc(100vh-65px)] flex items-center justify-center p-4">
        
        <!-- Reduced max-width to max-w-3xl -->
        <div class="w-full max-w-3xl bg-gray-800 border border-gray-700 rounded-xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
            
            <!-- LEFT SIDEBAR (Info) -->
            <div class="w-full md:w-1/3 bg-gray-900/50 p-5 flex flex-col justify-between border-b md:border-b-0 md:border-r border-gray-700 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                
                <div>
                    <div class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-emerald-900/50 border border-emerald-700 mb-3 shadow-inner">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Onboard Staff</h3>
                    <p class="text-[11px] text-gray-400 mt-2 leading-relaxed">
                        Create a new account for a hotel employee. The user will be assigned an Employee Code and a unique QR token automatically.
                    </p>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-700/50">
                    <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold">Important Note</p>
                    <p class="text-[10px] text-gray-400 mt-1">
                        HODs and HR accounts have elevated permissions. Ensure you select the correct Role Level.
                    </p>
                </div>
            </div>

            <!-- RIGHT SIDE (Form) -->
            <div class="w-full md:w-2/3 p-5 sm:p-6">
                
                @if(session('success'))
                    <div class="mb-4 bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-3 py-2 rounded-lg shadow-sm text-xs flex items-start gap-2">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-900/50 border border-red-500 text-red-300 px-3 py-2 rounded-lg shadow-sm text-xs">
                        <ul class="list-disc pl-4 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}" class="space-y-3">
                    @csrf
                    
                    <!-- Name & Email Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Full Name</label>
                            <input type="text" name="name" required value="{{ old('name') }}" class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2 text-sm" placeholder="John Doe" style="color: white !important; -webkit-text-fill-color: white !important;">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
                            <input type="email" name="email" required value="{{ old('email') }}" class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2 text-sm" placeholder="john@hotelhq.test" style="color: white !important; -webkit-text-fill-color: white !important;">
                        </div>
                    </div>

                    <!-- Department, Role, Passwords Row -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Department</label>
                            <select name="department_id" required class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2 text-sm appearance-none cursor-pointer" style="color: white !important; -webkit-text-fill-color: white !important;">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Role Level</label>
                            <select name="role" required class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2 text-sm appearance-none cursor-pointer" style="color: white !important; -webkit-text-fill-color: white !important;">
                                @foreach($allowedRoles as $role)
                                    <option value="{{ $role }}">{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Password</label>
                            <input type="password" name="password" required class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2 text-sm" style="color: white !important; -webkit-text-fill-color: white !important;">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2 text-sm" style="color: white !important; -webkit-text-fill-color: white !important;">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button type="submit" class="group w-full relative inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-emerald-600 border-2 border-emerald-400 rounded-lg hover:bg-emerald-500 hover:border-emerald-300 hover:shadow-[0_0_15px_rgba(16,185,129,0.5)] hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Create Account
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>