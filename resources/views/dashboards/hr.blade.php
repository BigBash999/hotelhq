<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('HR Dashboard') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-400 mr-2">Role: <strong class="text-indigo-400">HR Manager</strong></span>
                
                <!-- EXPORT CSV BUTTON -->
                <a href="{{ route('export.attendance') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg shadow-blue-500/30 font-bold text-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export CSV
                </a>

                <!-- ADD EMPLOYEE BUTTON -->
                <a href="{{ route('users.create') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg shadow-lg shadow-emerald-500/30 font-bold text-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Employee
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 bg-gray-900 min-h-[calc(100vh-65px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            
            @if(session('success'))
                <div class="bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-3 py-2 rounded-lg shadow-lg text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- TOP ROW: FORCED 50/50 SPLIT USING INLINE FLEXBOX -->
            <div style="display: flex; flex-direction: row; gap: 16px; width: 100%;">
                
                <!-- Total Staff (Left Half) -->
                <div style="flex: 1; width: 50%;" class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg flex items-center justify-between relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500"></div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Total Staff</p>
                        <p class="text-[10px] text-indigo-400 font-medium uppercase tracking-wider">Across all departments</p>
                    </div>
                    <p class="text-7xl font-black text-white">{{ $totalEmployees }}</p>
                </div>

                <!-- Pending Leaves (Right Half - Bright Yellow) -->
                <div style="flex: 1; width: 50%;" class="bg-yellow-500 border border-yellow-600 rounded-xl p-6 shadow-[0_0_20px_rgba(234,179,8,0.15)] flex items-center justify-between relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-10">
                        <svg class="w-32 h-32 text-yellow-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="bg-yellow-900/20 p-1 rounded">
                                <svg class="w-5 h-5 text-yellow-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-sm font-black text-yellow-950 uppercase tracking-widest">Pending Leaves</p>
                        </div>
                        <p class="text-[10px] text-yellow-900 font-bold uppercase tracking-wider">Requires attention</p>
                    </div>
                    <p class="text-7xl font-black text-yellow-950 relative z-10">{{ $pendingLeaves->count() }}</p>
                </div>
            </div>

            <!-- SECOND ROW: 5 Departments -->
            <div style="display: flex; flex-direction: row; gap: 12px; width: 100%;">
                @foreach($departments as $dept)
                    @if($dept->code !== 'HR') 
                    <div style="flex: 1;" class="bg-gray-800 border border-gray-700 rounded-xl p-3 shadow-sm flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-gray-600"></div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider truncate mb-1">{{ $dept->name }}</p>
                        <p class="text-2xl font-bold text-gray-200">{{ $dept->users_count }}</p>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- BOTTOM ROW: Tables -->
            <div style="display: flex; flex-direction: row; gap: 16px; height: calc(100vh - 350px);">
                
                <!-- LEFT COLUMN: Pending Leaves Table -->
                <div style="flex: 2;" class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg flex flex-col overflow-hidden">
                    <!-- Header with Search Bar -->
                    <div class="px-4 py-2 border-b border-gray-700 bg-gray-900/30 flex justify-between items-center">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Action Required</h3>
                        <input type="text" id="hrLeaveSearch" placeholder="Search employee..." class="bg-gray-800 border border-gray-600 text-white text-[10px] rounded p-1.5 focus:ring-indigo-500 focus:border-indigo-500 w-40">
                    </div>
                    
                    <div class="p-3 flex-1 overflow-y-auto">
                        @if($pendingLeaves->isEmpty())
                            <div class="h-full flex flex-col items-center justify-center text-gray-600">
                                <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs italic">No pending requests right now.</p>
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach($pendingLeaves as $leave)
                                    <!-- Added class="leave-row" and data-name for the search filter -->
                                    <div class="leave-row bg-gray-900/50 border border-gray-700 rounded-lg p-3 flex flex-col md:flex-row justify-between items-start md:items-center gap-3" 
                                         data-name="{{ strtolower($leave->user->name) }} {{ strtolower($leave->user->employee_code) }}">
                                        <div>
                                            <p class="font-bold text-sm text-gray-200">
                                                {{ $leave->user->name }} 
                                                <span class="text-[10px] font-normal text-gray-500 font-mono ml-1">({{ $leave->user->employee_code }})</span>
                                            </p>
                                            <p class="text-[11px] text-indigo-300 capitalize mt-0.5">
                                                {{ $leave->leave_type }} &bull; <strong>{{ $leave->start_date }}</strong> to <strong>{{ $leave->end_date }}</strong>
                                            </p>
                                            <p class="text-[11px] text-gray-400 italic mt-1">"{{ $leave->reason }}"</p>
                                        </div>
                                        
                                        <div class="flex gap-2 w-full md:w-auto">
                                            <form method="POST" action="{{ route('leaves.update', $leave->id) }}" class="flex-1 md:flex-none">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <input type="hidden" name="hr_note" value="Approved by HR">
                                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-3 py-1.5 rounded text-[10px] transition uppercase tracking-wider">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('leaves.update', $leave->id) }}" class="flex-1 md:flex-none">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <input type="hidden" name="hr_note" value="Rejected by HR">
                                                <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white font-bold px-3 py-1.5 rounded text-[10px] transition uppercase tracking-wider">Reject</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- RIGHT COLUMN: Recent Activity -->
                <div style="flex: 1;" class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg flex flex-col overflow-hidden">
                    <div class="px-4 py-2 border-b border-gray-700 bg-gray-900/30">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Recent Activity</h3>
                    </div>
                    <div class="p-3 flex-1 overflow-y-auto space-y-2">
                        @forelse($recentLeaves as $leave)
                            <div class="flex justify-between items-center bg-gray-900/30 p-2 rounded border border-gray-700/50">
                                <div>
                                    <p class="text-xs font-bold text-gray-200">{{ $leave->user->name }}</p>
                                    <p class="text-[10px] text-gray-500 capitalize">{{ $leave->leave_type }} ({{ $leave->start_date }})</p>
                                </div>
                                @if($leave->status == 'approved')
                                    <span class="bg-emerald-900/50 text-emerald-400 border border-emerald-700 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase">Approved</span>
                                @else
                                    <span class="bg-red-900/50 text-red-400 border border-red-700 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase">Rejected</span>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500 text-xs italic text-center mt-4">No recent activity.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Search Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('hrLeaveSearch');
            
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    let value = this.value.toLowerCase();
                    let rows = document.querySelectorAll('.leave-row');
                    
                    rows.forEach(row => {
                        let text = row.getAttribute('data-name');
                        if (text.includes(value)) {
                            row.style.display = 'flex'; // Retain the flex layout
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>