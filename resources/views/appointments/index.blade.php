<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900">
                    <div class="container my-5">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-lg">
                            <div class="bg-blue-500 text-white p-4 flex justify-between items-center rounded-t-lg">
                                <h1 class="text-lg font-bold">Appointments List</h1>
                                <a href="{{ route('appointments.create') }}"
                                    class="bg-white text-blue-500 hover:text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                                    <i class="bi bi-plus-circle"></i> Add Appointment
                                </a>
                            </div>

                            <x-message></x-message> <!-- If you want to display flash messages like success/error -->

                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border-collapse border border-gray-300">
                                        <thead class="bg-blue-100 text-blue-800">
                                            <tr>
                                                <th class="border border-gray-300 px-4 py-2">#</th>
                                                <th class="border border-gray-300 px-4 py-2">Patient Name</th>
                                                <th class="border border-gray-300 px-4 py-2">Doctor Name</th>
                                                <th class="border border-gray-300 px-4 py-2">Disease</th>
                                                <th class="border border-gray-300 px-4 py-2">Category</th>
                                                <th class="border border-gray-300 px-4 py-2">Appointment Date & Time</th>
                                                <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($appointments as $appointment)
                                                <tr class="even:bg-gray-100">
                                                    <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $appointment->user->name }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $appointment->doctor->user->name }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $appointment->disease }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $appointment->category }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $appointment->appointment_datetime->format('Y-m-d H:i') }}</td>
                                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                                        <div class="flex justify-center gap-2">
                                                            <a href="{{ route('appointments.edit', $appointment->id) }}"
                                                                class="text-blue-500 hover:text-blue-700 px-3 py-1 rounded-lg text-sm border border-blue-500">
                                                                <i class="bi bi-pencil-square"></i> Edit
                                                            </a>
                                                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                    class="text-red-500 hover:text-red-700 px-3 py-1 rounded-lg text-sm border border-red-500">
                                                                    <i class="bi bi-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center text-red-500 px-4 py-2" colspan="6">No appointments found!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
