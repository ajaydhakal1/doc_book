<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900">
                    <div class="container my-5">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-lg">
                            <div class="bg-blue-500 text-white p-4 flex justify-between items-center rounded-t-lg">
                                <h1 class="text-lg font-bold">Schedules List</h1>
                                <a href="{{ route('schedules.create') }}"
                                    class="bg-white text-blue-500 hover:text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                                    <i class="bi bi-plus-circle"></i> Add Schedule
                                </a>
                            </div>

                            <x-message></x-message> <!-- For flash messages like success/error -->

                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border-collapse border border-gray-300">
                                        <thead class="bg-blue-100 text-blue-800">
                                            <tr>
                                                <th class="border border-gray-300 px-4 py-2">#</th>
                                                <th class="border border-gray-300 px-4 py-2">Doctor Name</th>
                                                <th class="border border-gray-300 px-4 py-2">Date</th>
                                                <th class="border border-gray-300 px-4 py-2">Time</th>
                                                <th class="border border-gray-300 px-4 py-2">Status</th>
                                                <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($groupedSchedules as $doctorId => $schedules)
                                                @foreach ($schedules as $schedule)
                                                    <tr class="even:bg-gray-100">
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            {{ $loop->parent->iteration }}.{{ $loop->iteration }}
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            {{ $schedule->doctor->user->name }}
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            {{ \Carbon\Carbon::parse($schedule->date)->format('d-m-Y') }}
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            {{ ($schedule->status) }}
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                                            <div class="flex justify-center gap-2">
                                                                <!-- Edit Button -->
                                                                <a href="{{ route('schedules.edit', $schedule->id) }}"
                                                                    class="text-blue-500 hover:text-blue-700 px-3 py-1 rounded-lg text-sm border border-blue-500">
                                                                    <i class="bi bi-pencil-square"></i> Edit
                                                                </a>

                                                                <!-- Delete Button -->
                                                                <form action="{{ route('schedules.destroy', $schedule->id) }}"
                                                                    method="POST" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="text-red-500 hover:text-red-700 px-3 py-1 rounded-lg text-sm border border-red-500"
                                                                        onclick="return confirm('Are you sure you want to delete this schedule?');">
                                                                        <i class="bi bi-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
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