<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2
                class="font-semibold text-3xl text-gray-100 leading-tight bg-gradient-to-r from-indigo-800 to-purple-900 p-4 rounded-xl shadow-2xl transform transition-all hover:scale-[1.02]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 inline-block mr-3 text-indigo-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-900 via-gray-800 to-black min-h-screen">
        <div class="container mx-auto px-4 lg:px-8">
            {{-- Search Feature --}}
            <form method="GET" action="{{ route('dashboard') }}" class="mb-8">
                <div class="flex items-center space-x-4">
                    <div class="relative flex-grow">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute top-1/2 left-4 transform -translate-y-1/2 h-5 w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="query" value="{{ request('query') }}"
                            placeholder="Search users, schedules, appointments..."
                            class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-transparent bg-gray-800 text-gray-200 
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent 
                                   transition-all duration-300 ease-in-out">
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow-lg 
                               hover:bg-indigo-700 hover:shadow-xl transform hover:scale-105 
                               transition-all duration-300 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Search</span>
                    </button>
                </div>
            </form>

            {{-- Search Results with Enhanced Styling --}}
            @if (!is_null($searchResults))
                <div
                    class="bg-gray-800 p-8 rounded-xl shadow-2xl border-l-4 border-indigo-500 mb-8 
                            transform transition-all hover:shadow-3xl hover:scale-[1.01]">
                    <h3 class="text-2xl font-bold text-indigo-400 mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        Search Results for "{{ request('query') }}"
                    </h3>

                    {{-- Sections with Improved Spacing and Icons --}}
                    @foreach (['users', 'schedules', 'appointments'] as $section)
                        <div class="mb-6">
                            <h4 class="text-xl font-semibold text-gray-300 mb-4 flex items-center">
                                @switch($section)
                                    @case('users')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-green-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a8 8 0 00-8 8h16a8 8 0 00-8-8z" />
                                        </svg>
                                        Users
                                    @break

                                    @case('schedules')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-blue-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Schedules
                                    @break

                                    @case('appointments')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-purple-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Appointments
                                    @break
                                @endswitch
                            </h4>
                            @forelse($searchResults[$section] as $item)
                                <div
                                    class="bg-gray-700 rounded-lg p-3 mb-2 hover:bg-gray-600 transition-colors duration-300">
                                    @switch($section)
                                        @case('users')
                                            <p class="text-gray-300">
                                                <strong class="text-indigo-400">Name:</strong> {{ $item->name }}
                                                | <strong class="text-indigo-400">Email:</strong> {{ $item->email }}
                                            </p>
                                        @break

                                        @case('schedules')
                                            <p class="text-gray-300">
                                                <strong class="text-indigo-400">Date:</strong> {{ $item->date }}
                                                | <strong class="text-indigo-400">Doctor:</strong>
                                                {{ $item->doctor->user->name }}
                                            </p>
                                        @break

                                        @case('appointments')
                                            <p class="text-gray-300">
                                                <strong class="text-indigo-400">Patient:</strong>
                                                {{ $item->patient->user->name }}
                                                | <strong class="text-indigo-400">Doctor:</strong>
                                                {{ $item->doctor->user->name }}
                                                | <strong class="text-indigo-400">Date:</strong> {{ $item->schedule->date }}
                                            </p>
                                        @break
                                    @endswitch
                                </div>
                                @empty
                                    <p class="text-gray-500 italic">No {{ $section }} found.</p>
                                @endforelse
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Role-Based Dashboards with Subtle Animations --}}
                @if (auth()->user()->hasRole('Admin'))
                    <div class="transform transition-all hover:scale-[1.02] hover:shadow-2xl">
                        <x-admin-dashboard :data="$data" />
                    </div>
                @endif

                @if (auth()->user()->hasRole('Doctor'))
                    <div class="transform transition-all hover:scale-[1.02] hover:shadow-2xl">
                        <x-doctor-dashboard :schedules="$data['doctor_schedules']" />
                    </div>
                @endif

                @if (auth()->user()->hasRole('Patient'))
                    <div class="transform transition-all hover:scale-[1.02] hover:shadow-2xl">
                        <x-patient-dashboard :appointments="$data['my_appointments']" />
                    </div>
                @endif
            </div>
        </div>
    </x-app-layout>
