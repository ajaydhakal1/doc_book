<div
    class="bg-white dark:bg-gray-800 dark:border-gray-700 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700/50 transition-all duration-300 ease-in-out">
    <div class="p-6 lg:p-8">
        <h3 class="text-gray-900 dark:text-white font-bold text-3xl mb-6">Admin Dashboard</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-dashboard-card title="Total Patients" :value="$data['total_patients']" color="blue" />
            <x-dashboard-card title="Total Doctors" :value="$data['total_doctors']" color="blue" />
            <x-dashboard-card title="Total Schedules" :value="$data['total_schedules']" color="blue" />
            <x-dashboard-card title="Total Appointments" :value="$data['total_appointments']" color="blue" />
        </div>
        <div class="mt-8">
            <h4 class="text-gray-800 dark:text-white/80 mb-4 text-xl">Today's Schedules</h4>
            <ul>
                @forelse($data['todays_schedules'] as $schedule)
                    <li class="text-gray-700 dark:text-gray-200 mb-2 transition-colors duration-200">
                        <strong class="text-indigo-600 dark:text-indigo-400">Doctor:</strong>
                        {{ $schedule->doctor->user->name }}<br>
                        <strong class="text-indigo-600 dark:text-indigo-400">Date:</strong> {{ $schedule->date }}<br>
                        <strong class="text-indigo-600 dark:text-indigo-400">Time:</strong> {{ $schedule->start_time }}
                        - {{ $schedule->end_time }}
                    </li>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">No schedules found for today.</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>
