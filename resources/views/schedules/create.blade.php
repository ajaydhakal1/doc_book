<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Create Schedule</h1>

        <x-message></x-message>

        <form action="{{ route('schedules.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="doctor_id" class="block font-medium text-gray-700">Select Doctor</label>
                <select id="doctor_id" name="doctor_id" required class="w-full mt-1 border-gray-300 rounded-lg">
                    <option value="" disabled selected>Select a doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <h2 class="font-medium text-lg mb-4">Add Schedules</h2>

            <div id="schedule-container">
                <div class="schedule-item mb-4 border-t pt-4">
                    <h3 class="font-bold text-gray-700 mb-2">Schedule 1</h3>

                    <div class="mb-4">
                        <label for="date_1" class="block font-medium text-gray-700">Date</label>
                        <input type="date" name="schedules[0][date]" id="date_1"
                            class="w-full mt-1 border-gray-300 rounded-lg" value="{{ old('schedules.0.date') }}"
                            required>
                        @error("schedules.0.date")
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/3">
                            <label for="start_time_1" class="block font-medium text-gray-700">Start Time</label>
                            <input type="time" name="schedules[0][start_time]" id="start_time_1"
                                class="w-full mt-1 border-gray-300 rounded-lg"
                                value="{{ old('schedules.0.start_time') }}" required>
                            @error("schedules.0.start_time")
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="w-1/3">
                            <label for="end_time_1" class="block font-medium text-gray-700">End Time</label>
                            <input type="time" name="schedules[0][end_time]" id="end_time_1"
                                class="w-full mt-1 border-gray-300 rounded-lg" value="{{ old('schedules.0.end_time') }}"
                                required>
                            @error("schedules.0.end_time")
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="w-1/3">
                            <label for="status_1" class="block font-medium text-gray-700">Status</label>
                            <select name="schedules[0][status]" id="status_1"
                                class="w-full mt-1 border-gray-300 rounded-lg" required>
                                <option value="available" {{ old('schedules.0.status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="booked" {{ old('schedules.0.status') == 'booked' ? 'selected' : '' }}>
                                    Booked</option>
                                <option value="unavailable" {{ old('schedules.0.status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                            @error("schedules.0.status")
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <button type="button" id="add-schedule"
                    class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                    Add Another Schedule
                </button>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Save Schedules
                </button>
            </div>
        </form>
    </div>

    <script>
        let scheduleIndex = 1;

        document.getElementById('add-schedule').addEventListener('click', function () {
            const scheduleContainer = document.getElementById('schedule-container');
            const newSchedule = `
                <div class="schedule-item mb-4 border-t pt-4">
                    <h3 class="font-bold text-gray-700 mb-2">Schedule ${scheduleIndex + 1}</h3>
                    
                    <div class="mb-4">
                        <label for="date_${scheduleIndex + 1}" class="block font-medium text-gray-700">Date</label>
                        <input type="date" name="schedules[${scheduleIndex}][date]" id="date_${scheduleIndex + 1}" 
                            class="w-full mt-1 border-gray-300 rounded-lg" required>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/3">
                            <label for="start_time_${scheduleIndex + 1}" class="block font-medium text-gray-700">Start Time</label>
                            <input type="time" name="schedules[${scheduleIndex}][start_time]" id="start_time_${scheduleIndex + 1}" 
                                class="w-full mt-1 border-gray-300 rounded-lg" required>
                        </div>

                        <div class="w-1/3">
                            <label for="end_time_${scheduleIndex + 1}" class="block font-medium text-gray-700">End Time</label>
                            <input type="time" name="schedules[${scheduleIndex}][end_time]" id="end_time_${scheduleIndex + 1}" 
                                class="w-full mt-1 border-gray-300 rounded-lg" required>
                        </div>

                        <div class="w-1/3">
                            <label for="status_${scheduleIndex + 1}" class="block font-medium text-gray-700">Status</label>
                            <select name="schedules[${scheduleIndex}][status]" id="status_${scheduleIndex + 1}" 
                                class="w-full mt-1 border-gray-300 rounded-lg" required>
                                <option value="available">Available</option>
                                <option value="booked">Booked</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            scheduleContainer.insertAdjacentHTML('beforeend', newSchedule);
            scheduleIndex++;
        });
    </script>
</x-app-layout>