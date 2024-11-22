<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-700">
                <div class="p-6 text-gray-200">
                    <div class="container">
                        <!-- Header Section -->
                        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                            <div
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 flex justify-between items-center rounded-t-lg">
                                <h1 class="text-xl font-bold">Schedules List</h1>
                                <a href="{{ route('schedules.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg shadow-sm text-sm font-medium hover:bg-gray-200 transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Schedule
                                </a>
                            </div>

                            <!-- Flash Messages -->
                            <x-message></x-message>

                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table
                                        class="min-w-full border-collapse border border-gray-700 rounded-lg overflow-hidden">
                                        <thead class="bg-gray-700/50 text-gray-200">
                                            <tr>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">#</th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">Doctor Name
                                                </th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">Date</th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">Time</th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">Status</th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold text-center">
                                                    Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800">
                                            @forelse ($groupedSchedules as $doctorId => $schedules)
                                                @foreach ($schedules as $schedule)
                                                    <tr
                                                        class="even:bg-gray-700/30 hover:bg-gray-700/50 transition duration-150">
                                                        <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                            {{ $loop->parent->iteration }}.{{ $loop->iteration }}
                                                        </td>
                                                        <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                            {{ $schedule->doctor->user->name }}
                                                        </td>
                                                        <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                            {{ \Carbon\Carbon::parse($schedule->date)->format('d-m-Y') }}
                                                        </td>
                                                        <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                        </td>
                                                        <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                            <span
                                                                class="px-2 py-1 rounded-full text-xs font-semibold
                                                                {{ $schedule->status == 'booked' ? 'bg-green-100 text-green-800' : ($schedule->status == 'unavailable' ? 'bg-red-300 text-gray-700' : 'bg-yellow-100 text-yellow-800') }}">
                                                                {{ ucfirst($schedule->status ?? 'unknown') }}
                                                            </span>
                                                        </td>
                                                        <td class="border border-gray-700 px-4 py-3">
                                                            <div class="flex justify-center gap-3">
                                                                <!-- Edit Button -->
                                                                <a href="{{ route('schedules.edit', $schedule->id) }}"
                                                                    class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-blue-400 rounded-lg hover:bg-blue-900/50 transition-colors duration-200">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                    Edit
                                                                </a>

                                                                <!-- Delete Button -->
                                                                <form action="{{ route('schedules.destroy', $schedule->id) }}"
                                                                    method="POST" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="inline-flex items-center px-3 py-1.5 border border-red-500 text-red-400 rounded-lg hover:bg-red-900/50 transition-colors duration-200"
                                                                        onclick="return confirm('Are you sure you want to delete this schedule?');">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="h-4 w-4 mr-1.5" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @empty
                                                <tr>
                                                    <td class="text-center text-red-400 px-4 py-3" colspan="6">
                                                        No schedules found!
                                                    </td>
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