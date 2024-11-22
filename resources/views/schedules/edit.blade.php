<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg">
                <!-- Header -->
                <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center rounded-t-lg">
                    <h1 class="text-xl font-bold">Edit Doctor's Schedule</h1>
                    <a href="{{ route('schedules.index') }}"
                        class="bg-white text-blue-600 hover:text-blue-800 hover:bg-gray-100 px-4 py-2 rounded-lg text-sm font-medium transition">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>

                <!-- Form Section -->
                <div class="p-6">
                    <form action="{{ route('schedules.update', [$doctor->id, $schedule->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Doctor Name -->
                        <div class="mb-6">
                            <label for="doctor_name" class="block text-sm font-medium text-gray-700">Doctor Name</label>
                            <input type="text" id="doctor_name" value="{{ $doctor->user->name }}" disabled
                                class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-600">
                        </div>

                        <!-- Schedules Section -->
                        <div class="space-y-6">
                            @foreach ($doctor->schedules as $schedule)
                                <div
                                    class="p-4 border border-gray-300 rounded-lg shadow-sm bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Date -->
                                        <div>
                                            <label for="date_{{ $schedule->id }}"
                                                class="block text-sm font-medium text-gray-700">Date</label>
                                            <input type="date" id="date_{{ $schedule->id }}"
                                                name="schedules[{{ $schedule->id }}][date]"
                                                value="{{ old('schedules.' . $schedule->id . '.date', $schedule->date) }}"
                                                min="{{Carbon\Carbon::now()->format('Y-m-d')}}"
                                                class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <!-- Start Time -->
                                        <div>
                                            <label for="start_time_{{ $schedule->id }}"
                                                class="block text-sm font-medium text-gray-700">Start Time</label>
                                            <input type="time" id="start_time_{{ $schedule->id }}"
                                                name="schedules[{{ $schedule->id }}][start_time]"
                                                value="{{ old('schedules.' . $schedule->id . '.start_time', $schedule->start_time) }}"
                                                class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <!-- End Time -->
                                        <div>
                                            <label for="end_time_{{ $schedule->id }}"
                                                class="block text-sm font-medium text-gray-700">End Time</label>
                                            <input type="time" id="end_time_{{ $schedule->id }}"
                                                name="schedules[{{ $schedule->id }}][end_time]"
                                                value="{{ old('schedules.' . $schedule->id . '.end_time', $schedule->end_time) }}"
                                                class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="mt-4">
                                        <label for="status_{{ $schedule->id }}"
                                            class="block text-sm font-medium text-gray-700">Status</label>
                                        <select id="status_{{ $schedule->id }}"
                                            name="schedules[{{ $schedule->id }}][status]"
                                            class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="available" {{ old('schedules.' . $schedule->id . '.status', $schedule->status) === 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="booked" {{ old('schedules.' . $schedule->id . '.status', $schedule->status) === 'booked' ? 'selected' : '' }}>Booked</option>
                                            <option value="unavailable" {{ old('schedules.' . $schedule->id . '.status', $schedule->status) === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white hover:bg-blue-700 font-semibold px-4 py-2 rounded-lg transition">
                                Update Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>