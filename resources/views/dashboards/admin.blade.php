<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Super Admin Command Center') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs bg-red-900 text-red-300 border border-red-700 px-3 py-1 rounded-full font-bold uppercase tracking-widest">Restricted Access</span>
                <a href="{{ route('users.create') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg shadow-lg shadow-emerald-500/30 font-bold text-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Create User
                </a>
                                <a href="{{ route('admin.settings') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-lg font-bold text-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            
            @if(session('success'))
                <div class="bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-4 py-2 rounded-lg shadow-lg text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Top Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-800 border border-gray-700 p-4 rounded-xl shadow-lg border-l-4 border-l-indigo-500">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Users</p>
                    <p class="text-3xl font-black text-white mt-1">{{ $totalUsers }}</p>
                </div>
                <div class="bg-gray-800 border border-gray-700 p-4 rounded-xl shadow-lg border-l-4 border-l-purple-500">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Departments</p>
                    <p class="text-3xl font-black text-white mt-1">{{ $totalDepartments }}</p>
                </div>
                <div class="bg-gray-800 border border-gray-700 p-4 rounded-xl shadow-lg border-l-4 border-l-blue-500">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Shifts Scheduled</p>
                    <p class="text-3xl font-black text-white mt-1">{{ $totalRosters }}</p>
                </div>
                <div class="bg-gray-800 border border-gray-700 p-4 rounded-xl shadow-lg border-l-4 border-l-emerald-500">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Approved Leaves</p>
                    <p class="text-3xl font-black text-white mt-1">{{ $totalLeaves }}</p>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex flex-col lg:flex-row gap-4">
                
                <!-- User Directory (Takes 2/3) -->
                <div class="w-full lg:w-2/3 bg-gray-800 border border-gray-700 rounded-xl shadow-lg flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-gray-700 bg-gray-900/30 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wide">Master Employee Directory</h3>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto max-h-[60vh]">
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky top-0 bg-gray-900 text-gray-400 text-xs uppercase tracking-wider shadow-sm">
                                <tr>
                                    <th class="p-3 font-bold">Employee</th>
                                    <th class="p-3 font-bold">Department</th>
                                    <th class="p-3 font-bold text-right">Role Level</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($users as $u)
                                <tr class="hover:bg-gray-700/30 transition">
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            @if($u->avatar_url)
                                                <img src="{{ $u->avatar_url }}" class="h-8 w-8 rounded-full object-cover">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center text-xs font-bold text-white">
                                                    {{ substr($u->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-bold text-gray-200">{{ $u->name }}</p>
                                                <p class="text-[10px] text-gray-500 font-mono">{{ $u->employee_code ?? 'No Code' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3 text-sm text-gray-300">
                                        {{ $u->department ? $u->department->name : 'System' }}
                                    </td>
                                    <td class="p-3 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            <form method="POST" action="{{ route('admin.users.update-role', $u->id) }}" class="flex justify-end">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" class="bg-gray-900 border border-gray-600 text-xs text-gray-300 rounded p-1.5 focus:ring-indigo-500" onchange="this.form.submit()">
                                                    <option value="employee" {{ $u->hasRole('employee') ? 'selected' : '' }}>Employee</option>
                                                    <option value="hod" {{ $u->hasRole('hod') ? 'selected' : '' }}>HOD</option>
                                                    <option value="hr" {{ $u->hasRole('hr') ? 'selected' : '' }}>HR</option>
                                                    <option value="super_admin" {{ $u->hasRole('super_admin') ? 'selected' : '' }}>Super Admin</option>
                                                </select>
                                            </form>
                                            
                                            <!-- Reset Button (Hidden for yourself) -->
                                            @if(auth()->id() !== $u->id)
                                            <form method="POST" action="{{ route('admin.users.reset-password', $u->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to reset this user\'s password to password123?')"
                                                        title="Reset Password" 
                                                        class="bg-red-900/50 hover:bg-red-800 text-red-400 border border-red-700 rounded p-1.5 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- System Logs (Takes 1/3) -->
                <div class="w-full lg:w-1/3 bg-gray-800 border border-gray-700 rounded-xl shadow-lg flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-gray-700 bg-gray-900/30">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wide">Recent System Activity</h3>
                    </div>
                    <div class="p-4 flex-1 overflow-y-auto space-y-3 max-h-[60vh]">
                        @forelse($auditLogs as $log)
                            <div class="flex gap-3">
                                <div class="mt-1">
                                    <div class="h-6 w-6 rounded-full bg-indigo-900 flex items-center justify-center border border-indigo-700">
                                        <svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-200">
                                        <strong class="text-white">{{ $log->actor->name ?? 'System' }}</strong> 
                                        @if($log->action == 'roster.assigned')
                                            assigned a shift to an employee.
                                        @elseif($log->action == 'roster.removed')
                                            removed a shift assignment.
                                        @elseif($log->action == 'leave.approved')
                                            approved a leave request.
                                        @elseif($log->action == 'leave.rejected')
                                            rejected a leave request.
                                        @elseif($log->action == 'user.role_updated')
                                            updated a user's role to {{ $log->meta['new_role'] ?? 'unknown' }}.
                                        @else
                                            performed an action ({{ $log->action }}).
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 italic">No recent system activity found.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>