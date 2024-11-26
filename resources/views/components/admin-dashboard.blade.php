<div class="bg-gray-800/60 rounded-2xl shadow-2xl border border-gray-700/50">
    <div class="p-6 lg:p-8">
        <h3 class="text-white font-bold text-3xl mb-6">Admin Dashboard</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-dashboard-card title="Total Patients" :value="$data['total_patients']" color="blue" />
            <x-dashboard-card title="Total Doctors" :value="$data['total_doctors']" color="green" />
            <x-dashboard-card title="Total Schedules" :value="$data['total_schedules']" color="yellow" />
            <x-dashboard-card title="Total Appointments" :value="$data['total_appointments']" color="indigo" />
        </div>
        <div class="mt-8">
            <h4 class="text-white/80 mb-4 text-xl">Today's Schedules</h4>
            <ul>
                @forelse($data['todays_schedules'] as $schedule)
                    <li class="text-gray-300 mb-2">
                        <strong>Doctor:</strong> {{ $schedule->doctor->user->name }}<br>
                        <strong>Date:</strong> {{ $schedule->date }}<br>
                        <strong>Time:</strong> {{ $schedule->start_time }} - {{ $schedule->end_time }}
                    </li>
                @empty
                    <p class="text-gray-400">No schedules found for today.</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>
