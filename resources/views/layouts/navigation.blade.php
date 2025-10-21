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
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('prayers.index')" :active="request()->routeIs('prayers.index')">
                        {{ __('Prayer Times') }}
                    </x-nav-link>
                    <x-nav-link :href="route('qibla.index')" :active="request()->routeIs('qibla.index')">
                        {{ __('Qibla Compass') }}
                    </x-nav-link>
                    <x-nav-link :href="route('quran.index')" :active="request()->routeIs('quran.index')">
                        {{ __('Quran') }}
                    </x-nav-link>
                    <x-nav-link :href="route('hadith.index')" :active="request()->routeIs('hadith.index')">
                        {{ __('Hadith') }}
                    </x-nav-link>
                    <x-nav-link :href="route('fasting.index')" :active="request()->routeIs('fasting.index')">
                        {{ __('Fasting') }}
                    </x-nav-link>
                    <x-nav-link :href="route('mosques.index')" :active="request()->routeIs('mosques.index')">
                        {{ __('Nearby Mosque') }}
                    </x-nav-link>
                    <x-nav-link :href="route('duas.index')" :active="request()->routeIs('duas.index')">
                        {{ __('Duas') }}
                    </x-nav-link>
                    <x-nav-link :href="route('donations.index')" :active="request()->routeIs('donations.index')">
                        {{ __('Donations') }}
                    </x-nav-link>
                    <x-nav-link :href="route('zakat.index')" :active="request()->routeIs('zakat.index')">
                        {{ __('Zakat') }}
                    </x-nav-link>
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
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('prayers.index')" :active="request()->routeIs('prayers.index')">
                {{ __('Prayer Times') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('qibla.index')" :active="request()->routeIs('qibla.index')">
                {{ __('Qibla Compass') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('quran.index')" :active="request()->routeIs('quran.index')">
                {{ __('Quran') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('hadith.index')" :active="request()->routeIs('hadith.index')">
                {{ __('Hadith') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('fasting.index')" :active="request()->routeIs('fasting.index')">
                {{ __('Fasting') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('mosques.index')" :active="request()->routeIs('mosques.index')">
                {{ __('Nearby Mosque') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('duas.index')" :active="request()->routeIs('duas.index')">
                {{ __('Duas') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('donations.index')" :active="request()->routeIs('donations.index')">
                {{ __('Donations') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('zakat.index')" :active="request()->routeIs('zakat.index')">
                {{ __('Zakat') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('chatbot.index')" :active="request()->routeIs('chatbot.index')">
                {{ __('AI Chat') }}
            </x-responsive-nav-link>

            @if (auth()->check() && auth()->user()->is_admin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Admin') }}
                </x-responsive-nav-link>
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
