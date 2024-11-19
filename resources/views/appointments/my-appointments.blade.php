<x-app-layout>
    <x-slot name="title">My Appointments</x-slot>
    <div class="bg-gray-100 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">My Appointments</h1>

            @if ($appointments->isEmpty())
                <p class="text-center text-gray-500 text-lg">You have no upcoming appointments.</p>
            @else
                <div class="overflow-hidden bg-white shadow-md rounded-lg">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                                <th class="px-6 py-4 text-left">Doctor</th>
                                <th class="px-6 py-4 text-left">Date</th>
                                <th class="px-6 py-4 text-left">Time</th>
                                <th class="px-6 py-4 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appointment)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-6 py-4">
                                        {{ $appointment->doctor->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $appointment->appointment_date->format('F j, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $appointment->appointment_time }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold
                                                    {{ $appointment->status == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>