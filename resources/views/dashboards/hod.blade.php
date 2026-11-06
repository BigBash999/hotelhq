<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('HOD Roster Manager') }}
            </h2>
            <a href="{{ route('attendance.scanner') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg shadow-lg shadow-indigo-500/30 font-bold text-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                Open Scanner
            </a>
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

            <!-- HOD Shift Exchange Approvals -->
            @if(isset($pendingExchanges) && $pendingExchanges->count() > 0)
            <div class="bg-yellow-900/30 border-l-4 border-yellow-500 rounded-lg shadow-lg p-4">
                <h3 class="text-sm font-bold text-yellow-400 mb-3 uppercase tracking-wide flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Pending Shift Exchanges
                </h3>
                <div class="space-y-2">
                    @foreach($pendingExchanges as $exchange)
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-gray-800 p-3 rounded-lg border border-gray-700 gap-3">
                            <div>
                                <p class="text-sm text-gray-200"><strong class="text-white">{{ $exchange->requester->name }}</strong> and <strong class="text-white">{{ $exchange->target->name }}</strong> agreed to swap on <strong class="text-indigo-400">{{ $exchange->requesterRoster->date }}</strong>.</p>
                                <p class="text-xs text-gray-400 italic mt-1">"{{ $exchange->reason }}"</p>
                            </div>
                            <div class="flex gap-2 w-full md:w-auto">
                                <form method="POST" action="{{ route('shift-exchanges.hod', $exchange->id) }}" class="flex-1 md:flex-none">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="hod_status" value="approved">
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-1.5 rounded text-xs transition uppercase tracking-wider">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('shift-exchanges.hod', $exchange->id) }}" class="flex-1 md:flex-none">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="hod_status" value="rejected">
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white font-bold px-4 py-1.5 rounded text-xs transition uppercase tracking-wider">Reject</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-6 min-h-[70vh]">
                
                <!-- Side Menu -->
                <div class="w-full lg:w-1/4 bg-gray-800 border border-gray-700 rounded-xl shadow-lg flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-gray-700 bg-gray-900/30">
                        <h3 class="font-bold text-sm text-gray-200 uppercase tracking-wide mb-3">Available Staff</h3>
                        <input type="text" id="searchStaff" placeholder="Search name or code..." class="w-full bg-gray-900 border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 placeholder-gray-500">
                    </div>
                    
                    <div id="staffList" class="p-3 space-y-2 overflow-y-auto flex-1">
                        @foreach($employees as $emp)
                            <div class="p-2 bg-gray-900 border border-gray-700 rounded-lg hover:border-indigo-500 transition shadow-sm flex justify-between items-center cursor-grab active:cursor-grabbing" 
                                 data-id="{{ $emp->id }}" 
                                 data-name="{{ $emp->name }}">
                                
                                <div class="flex items-center gap-2">
                                    @if($emp->avatar_url)
                                        <img src="{{ $emp->avatar_url }}" class="h-8 w-8 rounded-full object-cover border border-gray-600 shrink-0">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                            {{ substr($emp->name, 0, 1) }}
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-bold text-sm text-gray-200">{{ $emp->name }}</p>
                                        <p class="text-[10px] text-gray-500 font-mono">{{ $emp->employee_code }}</p>
                                        
                                        <!-- Star Ratings -->
                                        <div class="flex items-center gap-1 mt-1">
                                            <div class="flex text-yellow-400 text-[10px]">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= round($emp->average_rating))
                                                        ★
                                                    @else
                                                        <span class="text-gray-600">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-[9px] text-gray-400 ml-1">{{ number_format($emp->average_rating, 1) }}</span>
                                        </div>

                                        <!-- Weekly Hours -->
                                        <div class="flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3 {{ $emp->weekly_hours > 45 ? 'text-red-400' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="hours-text text-[9px] {{ $emp->weekly_hours > 45 ? 'text-red-400 font-bold' : 'text-gray-400' }}" 
                                                  data-hours="{{ $emp->weekly_hours }}">
                                                {{ $emp->weekly_hours }} hrs / week
                                                @if($emp->is_overtime) (Overtime!) @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('evaluations.create', $emp->id) }}" title="Evaluate {{ $emp->name }}" class="text-gray-400 hover:text-yellow-400 transition p-1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- The Roster Grid -->
                <div class="w-full lg:w-3/4 bg-gray-800 border border-gray-700 rounded-xl shadow-lg flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-gray-700 bg-gray-900/30 flex justify-between items-center">
                        <h3 class="font-bold text-sm text-gray-200 uppercase tracking-wide">This Week's Roster</h3>
                        <span class="text-xs text-gray-500">Drag employees from the left.</span>
                    </div>

                    <div class="p-4 overflow-x-auto flex-1">
                        <div class="flex gap-3 min-w-[900px] h-full">
                            @foreach($dates as $date)
                                <div class="flex-1 flex flex-col gap-3">
                                    <!-- Date Header (Click to toggle Special Event) -->
                                    @php
                                        $isSpecial = $rosters->where('date', $date->format('Y-m-d'))->first()->is_special_event ?? false;
                                    @endphp
                                    <div onclick="toggleSpecialEvent('{{ $date->format('Y-m-d') }}')" 
                                         class="text-center font-bold text-xs p-2 rounded-lg border cursor-pointer hover:opacity-80 transition {{ $isSpecial ? 'bg-yellow-600 text-white border-yellow-500 shadow-[0_0_10px_rgba(234,179,8,0.4)]' : 'bg-gray-700 text-gray-200 border-gray-600' }}">
                                        {{ $date->format('D, M j') }}
                                        @if($isSpecial)
                                            <span class="block text-[8px] uppercase tracking-widest mt-0.5">Special Event</span>
                                        @endif
                                    </div>

                                    <!-- Shifts for this day -->
                                    @foreach($shifts as $shift)
                                        <div class="border border-gray-700 rounded-lg bg-gray-900/50 flex flex-col flex-1 overflow-hidden">
                                            <div class="text-[10px] uppercase font-bold text-gray-400 p-2 border-b border-gray-700 bg-gray-800/50">{{ $shift->name }}</div>
                                            
                                            <!-- Dropzone -->
                                            <div class="shift-dropzone min-h-[80px] p-2 space-y-1 overflow-y-auto" 
                                                 data-date="{{ $date->format('Y-m-d') }}" 
                                                 data-shift="{{ $shift->id }}">
                                                 
                                                 @foreach($rosters->where('date', $date->format('Y-m-d'))->where('shift_id', $shift->id) as $roster)
                                                    <div class="flex justify-between items-center p-1.5 bg-indigo-900/40 border border-indigo-700/50 text-xs rounded text-indigo-200 cursor-grab" data-id="{{ $roster->user_id }}">
                                                        <span class="truncate">{{ $roster->user->name ?? 'Unknown' }}</span>
                                                        <button onclick="removeAssignment({{ $roster->user_id }}, '{{ $date->format('Y-m-d') }}')" class="text-red-400 hover:text-red-300 font-bold ml-1 px-1 transition">
                                                            &times;
                                                        </button>
                                                    </div>
                                                 @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="js-data" 
         data-route="{{ route('hod.roster.assign') }}" 
         data-special-event-route="{{ route('hod.roster.special-event') }}"
         data-token="{{ csrf_token() }}" 
         style="display: none;">
    </div>

    <!-- Hidden Shift Data for Live Hours Calculation -->
    <div id="shifts-data" style="display: none;">
        @foreach($shifts as $shift)
            <div class="shift-data" data-id="{{ $shift->id }}" data-start="{{ $shift->start_time }}" data-end="{{ $shift->end_time }}"></div>
        @endforeach
    </div>

    <!-- Drag and Drop Logic with SweetAlerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            const routeUrl = document.getElementById('js-data').getAttribute('data-route');
            const specialEventRoute = document.getElementById('js-data').getAttribute('data-special-event-route');
            const csrfToken = document.getElementById('js-data').getAttribute('data-token');

            new Sortable(document.getElementById('staffList'), {
                group: { name: 'shared', pull: 'clone', put: false },
                animation: 150,
                sort: false,
            });

            document.querySelectorAll('.shift-dropzone').forEach(function (zone) {
                new Sortable(zone, {
                    group: 'shared',
                    animation: 150,
                    onAdd: function (evt) {
                        let item = evt.item; 
                        let employeeId = item.getAttribute('data-id');
                        let employeeName = item.getAttribute('data-name');
                        let date = evt.to.getAttribute('data-date');
                        let shiftId = evt.to.getAttribute('data-shift');
                        
                        fetch(routeUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                user_id: employeeId,
                                date: date,
                                shift_id: shiftId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                item.classList.remove('bg-gray-900', 'border-gray-700');
                                item.classList.add('bg-indigo-900/40', 'border-indigo-700/50', 'text-indigo-200');

                                // Re-format the dragged item to look like a clean pill
                                item.innerHTML = `
                                    <div class="flex justify-between items-center w-full">
                                        <span class="truncate">${employeeName}</span>
                                        <button onclick="removeAssignment(${employeeId}, '${date}')" class="text-red-400 hover:text-red-300 font-bold ml-1 px-1 transition">
                                            &times;
                                        </button>
                                    </div>
                                `;

                                // --- LIVE HOURS CALCULATION ---
                                let shiftData = document.querySelector(`.shift-data[data-id="${shiftId}"]`);
                                let start = shiftData.getAttribute('data-start');
                                let end = shiftData.getAttribute('data-end');

                                let startHours = parseInt(start.split(':')[0]);
                                let endHours = parseInt(end.split(':')[0]);
                                let shiftDuration = endHours - startHours;

                                let staffMenu = document.querySelector(`#staffList > div[data-id="${employeeId}"]`);
                                let hoursText = staffMenu.querySelector('.hours-text');
                                
                                if (hoursText) {
                                    let currentHours = parseFloat(hoursText.getAttribute('data-hours')) || 0;
                                    let newHours = currentHours + shiftDuration;
                                    
                                    hoursText.setAttribute('data-hours', newHours);
                                    hoursText.innerText = newHours + ' hrs / week';
                                    
                                    if (newHours > 45) {
                                        hoursText.classList.add('text-red-400', 'font-bold');
                                        hoursText.innerText += ' (Overtime!)';
                                    }
                                }

                                Swal.fire({
                                    toast: true, position: 'top-end', icon: 'success',
                                    title: "Assigned to shift",
                                    showConfirmButton: false, timer: 3000,
                                    background: '#1f2937', color: '#f3f4f6'
                                });
                            } else {
                                Swal.fire({
                                    toast: true, position: 'top-end', icon: 'error',
                                    title: data.message,
                                    showConfirmButton: false, timer: 4000,
                                    background: '#1f2937', color: '#f3f4f6'
                                });
                                item.remove(); 
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            item.remove();
                        });
                    },
                });
            });

            document.getElementById('searchStaff').addEventListener('keyup', function() {
                let value = this.value.toLowerCase();
                let items = document.querySelectorAll('#staffList > div');
                
                items.forEach(item => {
                    let text = item.textContent.toLowerCase();
                    item.style.display = text.includes(value) ? 'flex' : 'none';
                });
            });

            window.removeAssignment = function(userId, date) {
                Swal.fire({
                    title: 'Remove Assignment?',
                    text: "Are you sure you want to remove this shift?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#4b5563',
                    confirmButtonText: 'Yes, remove it!',
                    background: '#1f2937', color: '#f3f4f6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(routeUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                user_id: userId,
                                date: date,
                                shift_id: null 
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                window.location.reload(); 
                            } else {
                                Swal.fire({
                                    toast: true, position: 'top-end', icon: 'error',
                                    title: data.message,
                                    showConfirmButton: false, timer: 4000,
                                    background: '#1f2937', color: '#f3f4f6'
                                });
                            }
                        });
                    }
                });
            };

            // Toggle Special Event
            window.toggleSpecialEvent = function(date) {
                fetch(specialEventRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ date: date })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        window.location.reload();
                    } else {
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'warning',
                            title: data.message,
                            showConfirmButton: false, timer: 4000,
                            background: '#1f2937', color: '#f3f4f6'
                        });
                    }
                });
            };
        });
    </script>
</x-app-layout>