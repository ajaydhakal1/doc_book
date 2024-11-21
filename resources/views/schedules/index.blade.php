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
                                                <th class="border border-gray-300 px-4 py-2">Days Available</th>
                                                <th class="border border-gray-300 px-4 py-2">Time</th>
                                                <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($groupedSchedules as $doctorId => $schedules)
                                                <tr class="even:bg-gray-100">
                                                    <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        {{ $schedules->first()->doctor->user->name }}
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        @foreach ($schedules as $schedule)
                                                            @if ($schedule->status == 'available')
                                                                <!-- Display only available schedules -->
                                                                {{ $schedule->day }},
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        {{ \Carbon\Carbon::parse($schedules->first()->start_time)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($schedules->first()->end_time)->format('h:i A') }}
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                                        <div class="flex justify-center gap-2">
                                                            <!-- Edit Button -->
                                                            <a href="{{ route('schedules.edit', $schedules->first()->id) }}"
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