<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900">
                    <div class="container my-5">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-lg">
                            <div class="bg-blue-500 text-white p-4 flex justify-between items-center rounded-t-lg">
                                <h1 class="text-lg font-bold">Edit Doctor's Schedule</h1>
                                <a href="{{ route('schedules.index') }}"
                                    class="bg-white text-blue-500 hover:text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                            </div>

                            <div class="p-4">
                                <form action="{{ route('schedules.update', [$doctor->id, $schedule->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label for="doctor_name" class="block text-sm font-medium text-gray-700">Doctor
                                            Name</label>
                                        <input type="text" id="doctor_name" value="{{ $doctor->user->name }}" disabled
                                            class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-600">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Schedules</label>
                                        <div class="mt-2 space-y-4">
                                            @foreach ($doctor->schedules as $schedule)
                                                <div class="p-4 border border-gray-300 rounded-lg">
                                                    <h3 class="text-blue-600 font-semibold">Day: {{ $schedule->day }}</h3>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                                        <!-- Start Time -->
                                                        <div>
                                                            <label for="start_time_{{ $schedule->id }}"
                                                                class="block text-sm font-medium text-gray-700">Start
                                                                Time</label>
                                                            <input type="time" id="start_time_{{ $schedule->id }}"
                                                                name="schedules[{{ $schedule->id }}][start_time]"
                                                                value="{{ old('schedules.' . $schedule->id . '.start_time', $schedule->start_time) }}"
                                                                class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2">
                                                        </div>

                                                        <!-- End Time -->
                                                        <div>
                                                            <label for="end_time_{{ $schedule->id }}"
                                                                class="block text-sm font-medium text-gray-700">End
                                                                Time</label>
                                                            <input type="time" id="end_time_{{ $schedule->id }}"
                                                                name="schedules[{{ $schedule->id }}][end_time]"
                                                                value="{{ old('schedules.' . $schedule->id . '.end_time', $schedule->end_time) }}"
                                                                class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2">
                                                        </div>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="mt-3">
                                                        <label for="status_{{ $schedule->id }}"
                                                            class="block text-sm font-medium text-gray-700">Status</label>
                                                        <select name="schedules[{{ $schedule->id }}][status]"
                                                            class="form-select">
                                                            <option value="available" {{ old('schedules.' . $schedule->id . '.status', $schedule->status) === 'available' ? 'selected' : '' }}>Available</option>
                                                            <option value="booked" {{ old('schedules.' . $schedule->id . '.status', $schedule->status) === 'booked' ? 'selected' : '' }}>Booked</option>
                                                            <option value="unavailable" {{ old('schedules.' . $schedule->id . '.status', $schedule->status) === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg">
                                            Update Schedule
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>