<html>

<head>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <nav class="bg-gray-100 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Left Section -->
                <div class="flex items-center space-x-6">
                    <!-- Logo -->
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>

                    <!-- Links -->
                    <a href="{{ route('home') }}"
                        class="text-gray-800 dark:text-gray-200 hover:text-gray-500 dark:hover:text-gray-400">
                        Home
                    </a>
                    @if (Auth::user())
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-800 dark:text-gray-200 hover:text-gray-500 dark:hover:text-gray-400">
                            Dashboard
                        </a>
                        <a href="{{ route('doctors.index') }}"
                            class="text-gray-800 dark:text-gray-200 hover:text-gray-500 dark:hover:text-gray-400">
                            Doctors
                        </a>
                    @endif
                    @can('view patients')
                        <a href="{{ route('patients.index') }}"
                            class="text-gray-800 dark:text-gray-200 hover:text-gray-500 dark:hover:text-gray-400">
                            Patients
                        </a>
                    @endcan
                    @can('view own appointments')
                        <a href="{{ route('my-appointments') }}"
                            class="text-gray-800 dark:text-gray-200 hover:text-gray-500 dark:hover:text-gray-400">
                            My Appointments
                        </a>
                    @endcan
                    @can('create appointments')
                        <a class="btn btn-info" href="{{route('appointments.create')}}" role="button">Create
                            Appointment</a>
                    @endcan
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    @if (!auth()->user())
                        <!-- Login Button -->
                        <a href="{{ route('login') }}"
                            class="text-gray-800 dark:text-gray-200 hover:text-gray-500 dark:hover:text-gray-400">
                            Login
                        </a>

                        <!-- Register Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Register
                                <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute right-0 mt-2 w-48 bg-gray-500 dark:bg-gray-800 rounded-md shadow-lg z-20">
                                <a href="{{ route('patients.create') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Register as Patient
                                </a>
                                <a href="{{ route('doctors.create') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Register as Doctor
                                </a>
                            </div>
                        </div>
                    @else
                        <h3 class="px-3 text-white">Welcome, {{auth()->user()->name}}</h3>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                class="text-gray-800 dark:text-gray-100 px-3 py-2 rounded bg-red-500 hover:text-white dark:bg-red-500 :hover:text-white"
                                onclick="event.preventDefault();
                                                                        this.closest('form').submit();">
                                Logout
                            </a>
                        </form>

                    @endif
                </div>
            </div>
        </div>
    </nav>
</body>

</html>