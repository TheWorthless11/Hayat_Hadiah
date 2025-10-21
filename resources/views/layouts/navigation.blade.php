<nav x-data="{ open: false }">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="logo">
                        {{ config('app.name', 'Hayat Hadia') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-6 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>

                    <!-- START: Custom 'Worship' Dropdown -->
                    <div class="nav-item has-dropdown">
                        <a href="#" class="nav-link">
                            Worship
                            <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-content">
                                <a href="{{ route('prayers.index') }}" class="dropdown-item">Prayer Times</a>
                                <a href="{{ route('qibla.index') }}" class="dropdown-item">Qibla Compass</a>
                                <a href="{{ route('fasting.index') }}" class="dropdown-item">Fasting</a>
                                <a href="{{ route('mosques.index') }}" class="dropdown-item">Nearby Mosque</a>
                            </div>
                        </div>
                    </div>
                    <!-- END: Custom 'Worship' Dropdown -->

                    <!-- START: Custom 'Resources' Dropdown -->
                    <div class="nav-item has-dropdown">
                        <a href="#" class="nav-link">
                            Resources
                            <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-content">
                                <a href="{{ route('quran.index') }}" class="dropdown-item">Quran</a>
                                <a href="{{ route('hadith.index') }}" class="dropdown-item">Hadith</a>
                                <a href="{{ route('duas.index') }}" class="dropdown-item">Duas</a>
                            </div>
                        </div>
                    </div>
                    <!-- END: Custom 'Resources' Dropdown -->

                    <!-- START: Custom 'Giving' Dropdown -->
                    <div class="nav-item has-dropdown">
                        <a href="#" class="nav-link">
                            Giving
                            <svg class="fill-current h-4 w-4 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-content">
                                <a href="{{ route('donations.index') }}" class="dropdown-item">Donations</a>
                                <a href="{{ route('zakat.index') }}" class="dropdown-item">Zakat</a>
                            </div>
                        </div>
                    </div>
                    <!-- END: Custom 'Giving' Dropdown -->

                    <x-nav-link :href="route('chatbot.index')" :active="request()->routeIs('chatbot.index')">
                        {{ __('AI Chat') }}
                    </x-nav-link>

                    @if (auth()->check() && auth()->user()->is_admin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Admin') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            @auth
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Theme Toggle Button -->
                        <x-dropdown-link href="#" id="theme-toggle-btn" onclick="event.preventDefault();" style="display: flex; align-items: center;">
                            <!-- Moon Icon -->
                            <svg class="moon-icon h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                            <!-- Sun Icon -->
                            <svg class="sun-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.95-4.243l-1.59-1.59M3.75 12H6m4.243-4.95l1.59-1.59M12 6.375a5.625 5.625 0 11-11.25 0 5.625 5.625 0 0111.25 0z" /></svg>
                            <span class="ms-2">Toggle Theme</span>
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth

            @guest
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Register</a>
                @endif
            </div>
            @endguest

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')"> {{ __('Home') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('prayers.index')" :active="request()->routeIs('prayers.index')"> {{ __('Prayer Times') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('qibla.index')" :active="request()->routeIs('qibla.index')"> {{ __('Qibla Compass') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('quran.index')" :active="request()->routeIs('quran.index')"> {{ __('Quran') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('hadith.index')" :active="request()->routeIs('hadith.index')"> {{ __('Hadith') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('fasting.index')" :active="request()->routeIs('fasting.index')"> {{ __('Fasting') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('mosques.index')" :active="request()->routeIs('mosques.index')"> {{ __('Nearby Mosque') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('duas.index')" :active="request()->routeIs('duas.index')"> {{ __('Duas') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('donations.index')" :active="request()->routeIs('donations.index')"> {{ __('Donations') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('zakat.index')" :active="request()->routeIs('zakat.index')"> {{ __('Zakat') }} </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('chatbot.index')" :active="request()->routeIs('chatbot.index')"> {{ __('AI Chat') }} </x-responsive-nav-link>
            @if (auth()->check() && auth()->user()->is_admin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"> {{ __('Admin') }} </x-responsive-nav-link>
            @endif
        </div>

        @auth
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth

        @guest
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Log in') }}
                </x-responsive-nav-link>
                @if (Route::has('register'))
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endif
            </div>
        </div>
        @endguest
    </div>
</nav>

