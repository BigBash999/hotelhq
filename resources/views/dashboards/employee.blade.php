<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('My Dashboard') }}
            </h2>
            <span class="text-sm text-gray-400">Logged in as: <strong class="text-indigo-400">{{ $user->name }}</strong></span>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            
            <!-- Alerts -->
            @if(session('success'))
                <div class="bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-4 py-2 rounded-lg shadow-lg text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- TOP ROW: Profile Card + QR + Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                
                <!-- Profile Details (Span 4) -->
                <div class="lg:col-span-4 bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-12 w-12 bg-indigo-600 rounded-full flex items-center justify-center text-xl font-bold text-white shadow">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white leading-tight">{{ $user->name }}</h3>
                            <p class="text-xs text-indigo-400">{{ $user->getRoleNames()->first() == 'employee' ? 'Staff Member' : ucfirst($user->getRoleNames()->first()) }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between border-b border-gray-700 pb-1">
                            <span class="text-gray-400">Code:</span>
                            <span class="font-mono text-gray-200">{{ $user->employee_code }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-700 pb-1">
                            <span class="text-gray-400">Dept:</span>
                            <span class="text-gray-200">{{ $user->department ? $user->department->name : 'None' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-700 pb-1">
                            <span class="text-gray-400">Email:</span>
                            <span class="text-gray-200 truncate">{{ $user->email }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-1">
                            <span class="text-gray-400">Status:</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-900/50 text-green-400 border border-green-700">ACTIVE</span>
                        </div>
                    </div>
                </div>

                <!-- QR Code (Span 3) -->
                <div class="lg:col-span-3 bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-5 flex flex-col items-center justify-center relative">
                    <h3 class="text-sm font-bold text-gray-200 mb-3 tracking-wide uppercase">Attendance QR</h3>
                    <div class="bg-white p-2 rounded-lg shadow-inner">
                        {!! $qrCode !!}
                    </div>
                    <p class="text-[10px] text-gray-500 mt-2 text-center uppercase tracking-wider">Scan at shift start</p>
                </div>

                <!-- Chart (Span 5) -->
                <div class="lg:col-span-5 bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-5 flex flex-col">
                    <h3 class="text-sm font-bold text-gray-200 mb-3 tracking-wide uppercase flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        Performance Trend
                    </h3>
                    <div class="flex-1 relative w-full h-[150px]">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- MIDDLE ROW: Forms and Shifts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                
                <!-- Compact Leave Form (Fixed Layout) -->
                <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-5">
                    <h3 class="text-sm font-bold text-gray-200 mb-4 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Request Leave
                    </h3>
                    
                    <form method="POST" action="{{ route('leaves.store') }}" class="space-y-3">
                        @csrf
                        
                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="w-full md:w-1/4">
                                <label class="block text-xs font-medium text-gray-400 mb-1">Type</label>
                                <select name="leave_type" class="w-full bg-gray-900 border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2 appearance-none">
                                    <option value="annual">Annual</option>
                                    <option value="casual">Casual</option>
                                    <option value="sick">Sick</option>
                                    <option value="unpaid">Unpaid</option>
                                </select>
                            </div>
                            
                            <div class="w-full md:w-1/3">
                                <label class="block text-xs font-medium text-gray-400 mb-1">Start Date</label>
                                <input type="date" name="start_date" required class="w-full bg-gray-900 border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2 dark:[color-scheme:dark]">
                            </div>
                            
                            <div class="w-full md:w-1/3">
                                <label class="block text-xs font-medium text-gray-400 mb-1">End Date</label>
                                <input type="date" name="end_date" required class="w-full bg-gray-900 border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2 dark:[color-scheme:dark]">
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-3 items-end">
                            <div class="flex-1 w-full">
                                <label class="block text-xs font-medium text-gray-400 mb-1">Reason</label>
                                <input type="text" name="reason" required placeholder="Brief reason..." class="w-full bg-gray-900 border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2">
                            </div>
                            
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-2.5 px-6 rounded-lg transition duration-200 uppercase tracking-wider whitespace-nowrap">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Upcoming Shifts -->
                <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-5">
                    <h3 class="text-sm font-bold text-gray-200 mb-4 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        My Upcoming Shifts
                    </h3>
                    @if($myRosters->isEmpty())
                        <p class="text-gray-500 text-sm italic">No upcoming shifts scheduled.</p>
                    @else
                        <div class="space-y-2 max-h-[200px] overflow-y-auto pr-1">
                            @foreach($myRosters as $roster)
                                <div class="bg-gray-900/50 border border-gray-700 rounded-lg p-2 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-gray-200 text-sm">{{ \Carbon\Carbon::parse($roster->date)->format('l, M j') }}</p>
                                        <p class="text-xs text-indigo-300">{{ $roster->shift->name }} ({{ $roster->shift->start_time }} - {{ $roster->shift->end_time }})</p>
                                    </div>
                                    <button onclick="document.getElementById('swap-form-{{ $roster->id }}').classList.toggle('hidden')" class="text-[10px] bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded transition uppercase font-bold tracking-wider">
                                        Swap
                                    </button>
                                </div>
                                <!-- Hidden Swap Form -->
                                <div id="swap-form-{{ $roster->id }}" class="hidden mt-2 p-3 bg-gray-800 border border-gray-600 rounded-lg">
                                    <form method="POST" action="{{ route('shift-exchanges.store') }}" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="requester_date" value="{{ $roster->date }}">
                                        <select name="target_roster_id" required class="w-full bg-gray-900 border border-gray-600 text-gray-200 text-xs rounded p-1.5">
                                            <option value="">-- Choose colleague --</option>
                                            @foreach(\App\Models\Roster::with('user', 'shift')->where('date', $roster->date)->where('user_id', '!=', auth()->id())->get() as $colleagueRoster)
                                                <option value="{{ $colleagueRoster->id }}">{{ $colleagueRoster->user->name }} ({{ $colleagueRoster->shift->name }})</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="reason" required placeholder="Reason..." class="w-full bg-gray-900 border border-gray-600 text-gray-200 text-xs rounded p-1.5">
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold py-1.5 rounded uppercase">Send Request</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <!-- MY SCHEDULE CALENDAR -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg flex flex-col overflow-hidden mt-6">
                <div class="p-4 border-b border-gray-700 bg-gray-900/30 flex justify-between items-center">
                    <h3 class="font-bold text-sm text-gray-200 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        My Schedule ({{ now()->format('F Y') }})
                    </h3>
                </div>
                <div class="p-4">
                    <!-- Days Header -->
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; margin-bottom: 8px; text-align: center;">
                        <div class="text-[10px] font-bold text-gray-500 uppercase">Mon</div>
                        <div class="text-[10px] font-bold text-gray-500 uppercase">Tue</div>
                        <div class="text-[10px] font-bold text-gray-500 uppercase">Wed</div>
                        <div class="text-[10px] font-bold text-gray-500 uppercase">Thu</div>
                        <div class="text-[10px] font-bold text-gray-500 uppercase">Fri</div>
                        <div class="text-[10px] font-bold text-gray-500 uppercase">Sat</div>
                        <div class="text-[10px] font-bold text-gray-500 uppercase">Sun</div>
                    </div>
                    
                    <!-- Calendar Grid -->
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;">
                        @php
                            $startOfMonth = now()->startOfMonth();
                            $endOfMonth = now()->endOfMonth();
                            $startDayOfWeek = $startOfMonth->dayOfWeekIso; // 1 (Mon) to 7 (Sun)
                            $daysInMonth = $endOfMonth->day;
                        @endphp

                        <!-- Empty boxes for days before the 1st -->
                        @for ($i = 1; $i < $startDayOfWeek; $i++)
                            <div class="bg-gray-900/20 border border-gray-700/30 rounded-lg min-h-[70px]"></div>
                        @endfor

                        <!-- Days of the month -->
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $currentDate = now()->startOfMonth()->addDays($day - 1)->format('Y-m-d');
                                $isToday = $currentDate === now()->format('Y-m-d');
                                
                                $dayShift = $calendarRosters->where('date', $currentDate)->first();
                                
                                $isOnLeave = $calendarLeaves->filter(function($leave) use ($currentDate) {
                                    return $currentDate >= $leave->start_date && $currentDate <= $leave->end_date;
                                })->isNotEmpty();
                            @endphp

                            <div class="bg-gray-900 border {{ $isToday ? 'border-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.2)]' : 'border-gray-700' }} rounded-lg p-2 min-h-[70px] flex flex-col justify-between hover:border-gray-500 transition">
                                <div class="flex justify-between items-start">
                                    <span class="text-xs font-bold {{ $isToday ? 'text-indigo-400' : 'text-gray-400' }}">{{ $day }}</span>
                                    @if($isToday)
                                        <span class="text-[8px] bg-indigo-900 text-indigo-300 px-1 py-0.5 rounded font-bold uppercase">Today</span>
                                    @endif
                                </div>
                                
                                <div class="mt-1">
                                    @if($isOnLeave)
                                        <div class="text-[9px] bg-yellow-900/50 text-yellow-400 border border-yellow-700/50 px-1 py-0.5 rounded text-center font-bold">
                                            On Leave
                                        </div>
                                    @elseif($dayShift)
                                        <div class="text-[9px] {{ $dayShift->is_special_event ? 'bg-yellow-600/30 text-yellow-300 border-yellow-500' : 'bg-indigo-900/40 text-indigo-300 border-indigo-700/50' }} border px-1 py-0.5 rounded text-center font-bold leading-tight">
                                            {{ $dayShift->shift->name }}
                                            @if($dayShift->is_special_event)
                                                <span class="block text-[7px] uppercase tracking-widest mt-0.5">Event</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            <!-- BOTTOM ROW: Evaluations (Stars) & Swap Requests -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                
                <!-- Evaluations with Stars -->
                <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-5">
                    <h3 class="text-sm font-bold text-gray-200 mb-4 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        Feedback & Ratings
                    </h3>
                    @if($evaluations->isEmpty())
                        <p class="text-gray-500 text-sm italic">No evaluations received yet.</p>
                    @else
                        <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                            @foreach($evaluations as $eval)
                                <div class="bg-gray-900/50 border border-gray-700 rounded-lg p-3">
                                    <div class="flex justify-between items-center mb-1">
                                        <div class="flex text-yellow-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $eval->score)
                                                    ★
                                                @else
                                                    <span class="text-gray-600">★</span>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 bg-gray-800 px-2 py-0.5 rounded">{{ $eval->type }}</span>
                                    </div>
                                    <p class="text-sm text-gray-300 italic mb-1">"{{ $eval->comment }}"</p>
                                    <p class="text-[10px] text-gray-500 text-right">{{ \Carbon\Carbon::parse($eval->period_start)->format('M j') }} - {{ \Carbon\Carbon::parse($eval->period_end)->format('M j, Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Pending Swaps for ME -->
                <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg p-5">
                    <h3 class="text-sm font-bold text-yellow-400 mb-4 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Action Required
                    </h3>
                    @if($pendingExchangesForMe->count() > 0)
                        <div class="space-y-3">
                            @foreach($pendingExchangesForMe as $exchange)
                                <div class="bg-gray-900/50 border border-gray-700 p-3 rounded-lg">
                                    <p class="text-sm text-gray-200 mb-2"><strong class="text-white">{{ $exchange->requester->name }}</strong> wants to swap their <strong class="text-indigo-400">{{ $exchange->requesterRoster->shift->name }}</strong> for your <strong class="text-emerald-400">{{ $exchange->targetRoster->shift->name }}</strong> on <strong>{{ $exchange->requesterRoster->date }}</strong>.</p>
                                    <p class="text-xs text-gray-500 italic mb-3">"{{ $exchange->reason }}"</p>
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('shift-exchanges.peer', $exchange->id) }}" class="flex-1">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="peer_status" value="accepted">
                                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white px-2 py-1.5 rounded text-xs font-bold transition">Accept</button>
                                        </form>
                                        <form method="POST" action="{{ route('shift-exchanges.peer', $exchange->id) }}" class="flex-1">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="peer_status" value="declined">
                                            <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white px-2 py-1.5 rounded text-xs font-bold transition">Decline</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">No pending action required.</p>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            
            // Data passed from the controller
            const chartLabels = @json($chartLabels);
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Performance Score',
                        data: chartData,
                        borderColor: '#818cf8', // Indigo 400
                        backgroundColor: 'rgba(129, 140, 248, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#c7d2fe',
                        pointBorderColor: '#312e81',
                        pointRadius: 4,
                        fill: true,
                        tension: 0.3 // Makes the line curvy
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            ticks: { color: '#9ca3af', stepSize: 1 },
                            grid: { color: '#374151' }
                        },
                        x: {
                            ticks: { color: '#9ca3af' },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
</x-app-layout>