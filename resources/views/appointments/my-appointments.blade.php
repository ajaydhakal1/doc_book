<x-app-layout>
    <x-slot name="title">My Appointments</x-slot>
    <x-message></x-message>
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
                                @if (Auth::user()->hasRole('Patient'))
                                    <th class="px-6 py-4 text-left">Doctor</th>
                                @elseif(Auth::user()->hasRole('Doctor'))
                                    <th class="px-6 py-4 text-left">Patient</th>
                                @endif
                                <th class="px-6 py-4 text-left">Disease</th>
                                <th class="px-6 py-4 text-left">Date</th>
                                <th class="px-6 py-4 text-left">Time</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                @canany(['edit own appointment', 'delete own appointment'])
                                    <th class="px-6 py-4 text-left">Actions</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appointment)
                                <tr class="border-b hover:bg-gray-100">
                                    @if (Auth::user()->hasRole('Patient'))
                                        <td class="px-6 py-4">
                                            {{ $appointment->doctor->user->name }}
                                        </td>
                                    @elseif(Auth::user()->hasRole('Doctor'))
                                        <td class="px-6 py-4">
                                            {{ $appointment->patient->user->name }}
                                        </td>
                                    @endif
                                    <td class="px-6 py-4">
                                        {{ $appointment->disease }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $appointment->schedule->date }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->schedule->start_time)->format('g:i A') }}
                                        -
                                        {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->schedule->end_time)->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold
                                                                                                                                                                    {{ $appointment->status == 'booked' ? 'bg-green-100 text-green-800' : ($appointment->status == 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($appointment->status ?? 'unknown') }}
                                        </span>
                                    </td>

                                    @canany(['edit own appointment', 'delete own appointment'])
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2 gap-2">
                                                <!-- Edit Button -->
                                                @can('edit own appointment')
                                                    <form action="{{route('editMyAppointment', $appointment->id)}}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-xs">
                                                            Edit
                                                        </button>
                                                    </form>
                                                @endcan
                                                @can('delete own appointment')
                                                    <form action="{{route('myAppointments.destroy', $appointment->id)}}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-xs">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                    @endcanany
                                        </div>
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