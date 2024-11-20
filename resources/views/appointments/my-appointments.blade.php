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
                                <th class="px-6 py-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appointment)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-6 py-4">
                                        {{ $appointment->doctor->user->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $appointment->date }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->time)->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold
                                                                                {{ $appointment->status == 'booked' ? 'bg-green-100 text-green-800' : ($appointment->status == 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($appointment->status ?? 'unknown') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2 gap-2">
                                            <!-- Edit Button -->
                                            <form action="{{route('editMyAppointment', $appointment->id)}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-xs">
                                                    Edit
                                                </button>
                                            </form>

                                            <!-- Delete Button -->
                                            <form action="{{ route('deleteMyAppointment', $appointment->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-xs">
                                                    Delete
                                                </button>
                                            </form>
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