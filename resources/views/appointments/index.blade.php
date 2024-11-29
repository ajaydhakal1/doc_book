<x-app-layout>
    <!-- Main Content -->
    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Card Wrapper -->
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Header Section -->
                <div class="p-6 bg-gradient-to-r from-blue-400 to-blue-500 dark:from-blue-900 dark:to-blue-800">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Appointments List</h1>
                            <p class="text-gray-600 dark:text-blue-200 text-sm mt-1">
                                Manage patient appointments and doctor schedules
                            </p>
                        </div>
                        <a href="{{ route('specialities.choose') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold shadow-sm hover:bg-blue-700 dark:hover:bg-blue-800 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add Appointment
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                <x-message></x-message>

                <!-- Table Section -->
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Table Header -->
                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        #</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Patient Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Doctor Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Disease</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Appointment Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Time</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Actions</th>
                                </tr>
                            </thead>
                            <!-- Table Body -->
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($appointments as $appointment)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">
                                            {{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">
                                            {{ $appointment->patient->user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">
                                            {{ $appointment->doctor->user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">
                                            {{ $appointment->disease }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">
                                            {{ $appointment->date }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} -
                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">
                                            <form action="{{ route('appointments.updateStatus', $appointment->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()"
                                                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm">
                                                    @foreach (['pending', 'booked', 'rescheduled', 'cancelled', 'completed'] as $status)
                                                        <option value="{{ $status }}"
                                                            {{ $appointment->status === $status ? 'selected' : '' }}>
                                                            {{ ucfirst($status) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-medium">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('appointments.edit', $appointment->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('appointments.destroy', $appointment->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 border border-red-500 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition duration-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-6 py-8 text-center text-gray-700 dark:text-gray-400">
                                            <div class="flex flex-col items-center space-y-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                <span>No appointments found!</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $appointments->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
