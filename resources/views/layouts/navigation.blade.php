<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @can('view users')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                            {{ __('Users') }}
                        </x-nav-link>
                    @endcan
                    @can('view doctors')
                        <x-nav-link :href="route('doctors.index')" :active="request()->routeIs('doctors.index')">
                            {{ __('Doctors') }}
                        </x-nav-link>
                    @endcan
                    @can('view patients')
                        <x-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.index')">
                            {{ __('Patients') }}
                        </x-nav-link>
                    @endcan
                    @can('view specialities')
                        <x-nav-link :href="route('specialities.index')" :active="request()->routeIs('specialities.index')">
                            {{ __('Specialities') }}
                        </x-nav-link>
                    @endcan
                    @can('view schedules')
                        <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.index')">
                            {{ __('Schedules') }}
                        </x-nav-link>
                    @endcan
                    @can('view own schedules')
                        <x-nav-link :href="route('my-schedules')" :active="request()->routeIs('my-schedules')">
                            {{ __('My Schedules') }}
                        </x-nav-link>
                    @endcan
                    @if (auth()->user())
                        @if (auth()->user()->hasRole('Doctor') || auth()->user()->hasRole('Patient'))
                            <x-nav-link :href="route('my-appointments')" :active="request()->routeIs('my-appointments')">
                                {{ __('My Appointments') }}
                            </x-nav-link>
                        @endif
                    @endif
                    @can('view appointments')
                        <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.index')">
                            {{ __('Appointments') }}
                        </x-nav-link>
                    @endcan
                    @can('view permissions')
                        <x-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.index')">
                            {{ __('Permissions') }}
                        </x-nav-link>
                    @endcan
                    @can('view roles')
                        <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                            {{ __('Roles') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            @if (auth()->user())
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="flex items-center justify-between px-4 py-2">
                    <!-- Other elements like logo or navigation -->

                    <div class="relative" x-data="themeSwitcher()" @click.outside="menu = false">
                        <!-- Theme Toggle Button -->
                        <button
                            class="block rounded p-1 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300"
                            :class="menu ? 'text-gray-700 dark:text-gray-300' :
                                'text-gray-400 dark:text-gray-600 hover:text-gray-500'"
                            @click="menu = !menu">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="block h-5 w-5 dark:hidden">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="hidden h-5 w-5 dark:block">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z">
                                </path>
                            </svg>
                        </button>

                        <!-- Theme Options Menu -->
                        <div x-show="menu"
                            class="absolute right-0 z-10 flex origin-top-right flex-col rounded-md bg-white shadow-xl ring-1 ring-gray-900/5 dark:bg-gray-800"
                            style="display: none;" @click="menu = false">
                            <!-- Light Mode -->
                            <button
                                class="flex items-center gap-3 px-4 py-2 hover:rounded-t-md hover:bg-gray-100 dark:hover:bg-gray-700"
                                :class="theme === 'light' ? 'text-gray-900 dark:text-gray-100' :
                                    'text-gray-500 dark:text-gray-400'"
                                @click="lightMode()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z">
                                    </path>
                                </svg>
                                Light
                            </button>

                            <!-- Dark Mode -->
                            <button class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                :class="theme === 'dark' ? 'text-gray-900 dark:text-gray-100' :
                                    'text-gray-500 dark:text-gray-400'"
                                @click="darkMode()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z">
                                    </path>
                                </svg>
                                Dark
                            </button>

                            <!-- System Mode -->
                            <button
                                class="flex items-center gap-3 px-4 py-2 hover:rounded-b-md hover:bg-gray-100 dark:hover:bg-gray-700"
                                :class="theme === undefined ? 'text-gray-900 dark:text-gray-100' :
                                    'text-gray-500 dark:text-gray-400'"
                                @click="systemMode()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25">
                                    </path>
                                </svg>
                                System
                            </button>
                        </div>
                    </div>
                </div>

                <!-- For unauthenticated users -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">

                    <!-- Login Button -->
                    <x-nav-link :href="route('login')" class="me-4">
                        {{ __('Login') }}
                    </x-nav-link>

                    <!-- Register Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ __('Register') }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('patients.create')">
                                {{ __('Register as Patient') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('doctors.create')">
                                {{ __('Register as Doctor') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @can('view users')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
            @endcan
            @can('view doctors')
                <x-responsive-nav-link :href="route('doctors.index')" :active="request()->routeIs('doctors.index')">
                    {{ __('Doctors') }}
                </x-responsive-nav-link>
            @endcan
            @can('view patients')
                <x-responsive-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.index')">
                    {{ __('Patients') }}
                </x-responsive-nav-link>
            @endcan
            @can('view specialities')
                <x-responsive-nav-link :href="route('specialities.index')" :active="request()->routeIs('specialities.index')">
                    {{ __('Specialities') }}
                </x-responsive-nav-link>
            @endcan
            @can('view schedules')
                <x-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.index')">
                    {{ __('Schedules') }}
                </x-responsive-nav-link>
            @endcan
            @can('view own schedules')
                <x-responsive-nav-link :href="route('my-schedules')" :active="request()->routeIs('my-schedules')">
                    {{ __('My Schedules') }}
                </x-responsive-nav-link>
            @endcan
            @if (auth()->user())
                @if (auth()->user()->hasRole('Doctor') || auth()->user()->hasRole('Patient'))
                    <x-responsive-nav-link :href="route('my-appointments')" :active="request()->routeIs('my-appointments')">
                        {{ __('My Appointments') }}
                    </x-responsive-nav-link>
                @endif
            @endif
            @can('view appointments')
                <x-responsive-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.index')">
                    {{ __('Appointments') }}
                </x-responsive-nav-link>
            @endcan
            @can('view permissions')
                <x-responsive-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.index')">
                    {{ __('Permissions') }}
                </x-responsive-nav-link>
            @endcan
            @can('view roles')
                <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                    {{ __('Roles') }}
                </x-responsive-nav-link>
            @endcan
            @can('view payments')
                <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.index')">
                    {{ __('Payments') }}
                </x-responsive-nav-link>
            @endcan
            @can('create appointments')
                <x-responsive-nav-link :href="route('appointments.create')" :active="request()->routeIs('appointments.create')">
                    {{ __('Create Appointment') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        @if (auth()->user())
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endif
    </div>
</nav>