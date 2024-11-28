<div class="container mx-auto px-4 py-8">
    <div
        class="mt-8 bg-gray-800/60 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-700/50 transition duration-300 hover:shadow-3xl">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400 mr-3" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h3 class="text-2xl text-white font-bold">Patient Dashboard</h3>
            </div>

            <h4 class="text-white/80 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-300" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd" />
                </svg>
                My Appointments
            </h4>

            <ul class="space-y-3">
                @forelse($appointments as $appointment)
                    <li class="bg-gray-700/50 p-3 rounded-lg transition duration-300 hover:bg-gray-700/70 group">
                        <div class="flex items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-green-400 group-hover:text-green-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0111 16a13.937 13.937 0 015.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <strong class="text-gray-200 group-hover:text-white">Doctor:</strong>
                            <span
                                class="ml-2 text-gray-300 group-hover:text-white">{{ $appointment->doctor->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-green-400 group-hover:text-green-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <strong class="text-gray-200 group-hover:text-white">Date:</strong>
                            <span
                                class="ml-2 text-gray-300 group-hover:text-white">{{ $appointment->date ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-green-400 group-hover:text-green-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <strong class="text-gray-200 group-hover:text-white">Time:</strong>
                            <span class="ml-2 text-gray-300 group-hover:text-white">
                                {{ $appointment->start_time ?? 'N/A' }} -
                                {{ $appointment->end_time ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <i
                                class="fas fa-{{ $appointment->status === 'completed'
                                    ? 'check-circle text-green-500'
                                    : ($appointment->status === 'pending'
                                        ? 'clock text-yellow-500'
                                        : ($appointment->status === 'cancelled'
                                            ? 'times-circle text-red-500'
                                            : ($appointment->status === 'confirmed'
                                                ? 'exclamation-circle text-blue-500'
                                                : 'info-circle text-gray-500'))) }} mr-2"></i>
                            <strong class="text-gray-200 group-hover:text-white">Status:</strong>
                            <span class="ml-2 text-gray-300 group-hover:text-white">
                                {{ $appointment->status ?? 'N/A' }}
                            </span>
                        </div>
                    </li>
                @empty
                    <li
                        class="text-gray-400 bg-gray-700/50 p-4 rounded-lg text-center flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        No appointments found.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
