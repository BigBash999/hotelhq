<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Evaluate Employee') }}
            </h2>
            <a href="{{ url()->previous() }}" class="group flex items-center gap-2 bg-gray-800 hover:bg-gray-700 border border-gray-600 text-gray-300 hover:text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-sm text-sm font-medium">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </x-slot>

    <!-- Outer container with inline styling to force center -->
    <div class="bg-gray-900 w-full min-h-[calc(100vh-65px)] flex justify-center items-start pt-12 px-4">
        
        <!-- The Box: Forced to 450px width via inline style -->
        <div style="width: 100%; max-width: 450px;" class="bg-gray-800 border border-gray-700 rounded-2xl shadow-2xl overflow-hidden relative">
            
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500"></div>

            <div class="p-6">
                
                <!-- Centered Employee Info -->
                <div class="flex flex-col items-center justify-center mb-6 text-center">
                    <!-- Forced circle via inline styling -->
                    <div style="height: 60px; width: 60px; border-radius: 50%; flex-shrink: 0;" class="bg-gray-900 flex items-center justify-center text-2xl font-bold text-indigo-400 border border-gray-700 shadow-inner mb-3">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h3 class="text-lg font-bold text-white leading-tight">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-400 font-mono mt-1">{{ $user->employee_code }}</p>
                </div>

                <form method="POST" action="{{ route('evaluations.store', $user->id) }}" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Score (1-5)</label>
                            <input type="number" name="score" min="1" max="5" required placeholder="e.g. 5" class="w-full bg-gray-900 border border-gray-600 text-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 text-base font-bold text-center">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Type</label>
                            <select name="type" class="w-full bg-gray-900 border border-gray-600 text-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 text-sm appearance-none cursor-pointer">
                                <option value="performance">Performance</option>
                                <option value="attendance">Attendance</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Start Date</label>
                            <input type="date" name="period_start" required class="w-full bg-gray-900 border border-gray-600 text-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 text-sm dark:[color-scheme:dark]">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">End Date</label>
                            <input type="date" name="period_end" required class="w-full bg-gray-900 border border-gray-600 text-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 text-sm dark:[color-scheme:dark]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Feedback Notes</label>
                        <textarea name="comment" rows="3" required class="w-full bg-gray-900 border border-gray-600 text-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 placeholder-gray-600 text-sm" placeholder="What did they do well? What needs improvement?"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="group w-full relative inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-indigo-600 border-2 border-indigo-400 rounded-lg hover:bg-indigo-500 hover:border-indigo-300 hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Submit Evaluation
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>