<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('System Settings') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 bg-gray-800 hover:bg-gray-700 border border-gray-600 text-gray-300 hover:text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-sm text-sm font-medium">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </x-slot>

    <!-- Force Centering -->
    <div class="py-12 bg-gray-900 min-h-[calc(100vh-65px)] flex items-center justify-center p-4">
        
        <!-- Forced width of 500px -->
        <div style="width: 100%; max-width: 500px;" class="bg-gray-800 border border-gray-700 rounded-2xl shadow-2xl overflow-hidden relative">
            <!-- Top glowing line -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500"></div>

            <div class="p-6">
                
                <!-- Header -->
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-700">
                    <div class="h-10 w-10 rounded-lg bg-indigo-900/50 border border-indigo-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Global Configuration</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Changes apply system-wide.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-5 bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-3 py-2 rounded-lg shadow-sm text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Hotel Name -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Hotel Name</label>
                        <input type="text" name="hotel_name" value="{{ $settings['hotel_name'] }}" required 
                               class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 text-sm" 
                               style="color: white !important; -webkit-text-fill-color: white !important;">
                        @error('hotel_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Side-by-Side -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Max Weekly Hours</label>
                            <input type="number" name="max_weekly_hours" value="{{ $settings['max_weekly_hours'] }}" required 
                                   class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 text-sm" 
                                   style="color: white !important; -webkit-text-fill-color: white !important;">
                            <p class="text-[9px] text-gray-500 mt-0.5">Overtime limit.</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Late Grace (Mins)</label>
                            <input type="number" name="late_grace_period" value="{{ $settings['late_grace_period'] }}" required 
                                   class="w-full bg-gray-900 border border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 text-sm" 
                                   style="color: white !important; -webkit-text-fill-color: white !important;">
                            <p class="text-[9px] text-gray-500 mt-0.5">Late threshold.</p>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="pt-4 border-t border-gray-700 mt-4">
                        <button type="submit" class="group w-full relative inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white transition-all duration-200 bg-indigo-600 border-2 border-indigo-400 rounded-lg hover:bg-indigo-500 hover:border-indigo-300 hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>