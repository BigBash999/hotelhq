<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-700 sticky top-0 z-50 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Logo & Links -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:bg-indigo-500 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="font-black text-xl text-white tracking-tight">Hotel<span class="text-indigo-400">HQ</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white border-indigo-500">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side: Notifications & User Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-6">
                
                <!-- Notification Bell -->
                <div class="relative" x-data="{ openNotif: false }" @click.outside="openNotif = false">
                    <button @click="openNotif = !openNotif" class="relative text-gray-400 hover:text-white transition p-2 rounded-full hover:bg-gray-700/50 focus:outline-none mt-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border border-gray-900"></span>
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="openNotif" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95" 
                         class="absolute right-0 mt-2 bg-gray-800 border border-gray-700 rounded-lg shadow-2xl z-50 overflow-hidden" 
                         style="display: none; width: 400px !important;">
                        
                        <div class="px-4 py-3 text-xs text-gray-400 border-b border-gray-700 font-bold uppercase tracking-wider bg-gray-900/50">Notifications</div>
                        
                        <div class="max-h-80 overflow-y-auto w-full">
                            @forelse(auth()->user()->notifications->take(10) as $notification)
                                <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3 border-b border-gray-700/50 hover:bg-gray-700/50 transition w-full" style="white-space: normal !important;">
                                    <div class="flex items-start gap-3 w-full">
                                        <div class="mt-0.5 shrink-0">
                                            @if(($notification->data['icon'] ?? '') == 'check')
                                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @elseif(($notification->data['icon'] ?? '') == 'x')
                                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            @else
                                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 w-full">
                                            <p class="text-sm font-bold text-gray-200">{{ $notification->data['title'] ?? 'Update' }}</p>
                                            <p class="text-xs text-gray-400 mt-1 leading-snug">{{ $notification->data['message'] ?? '' }}</p>
                                            <p class="text-[10px] text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-8 text-center text-sm text-gray-500 italic">
                                    No new notifications.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 text-sm font-medium text-gray-300 hover:text-white transition focus:outline-none bg-gray-800 hover:bg-gray-700 border border-gray-600 rounded-full py-1 pl-1 pr-3 shadow-sm">
                            
                            <!-- User Avatar Circle -->
                            @if(Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" class="h-8 w-8 rounded-full object-cover border border-gray-600 shadow-inner">
                            @else
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs shadow-inner">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif

                            <!-- User Name & Role -->
                            <div class="flex flex-col items-start text-left">
                                <span class="text-xs font-bold leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-[9px] text-gray-400 uppercase tracking-wider">
                                    {{ Auth::user()->getRoleNames()->first() }}
                                </span>
                            </div>

                            <!-- Chevron -->
                            <svg class="w-4 h-4 text-gray-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-300 hover:bg-gray-700 hover:text-white">
                            {{ __('My Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-400 hover:bg-red-900/30 hover:text-red-300">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('Log Out') }}
                                </div>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-800 border-t border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-600">
            <div class="px-4 flex items-center gap-3">
                @if(Auth::user()->avatar_url)
                    <img src="{{ Auth::user()->avatar_url }}" class="h-10 w-10 rounded-full object-cover border border-gray-600">
                @else
                    <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
                
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                    {{ __('My Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-400 hover:text-red-300 hover:bg-gray-700">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>