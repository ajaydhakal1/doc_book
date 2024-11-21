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

            <h2 class="font-medium text-lg mb-2">Schedule</h2>

            @foreach($days as $day)
                <div class="mb-4 border-t pt-4">
                    <h3 class="font-bold text-gray-700">{{ $day }}</h3>
                    <input type="hidden" name="schedules[{{ $day }}][day]" value="{{ $day }}">

                    <div class="flex gap-4 mt-2">
                        <div class="w-1/3">
                            <label class="block font-medium text-gray-700">Start Time</label>
                            <input type="time" name="schedules[{{ $day }}][start_time]"
                                class="w-full mt-1 border-gray-300 rounded-lg"
                                value="{{ old('schedules.' . $day . '.start_time') }}" required>
                            @error("schedules.$day.start_time")
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="w-1/3">
                            <label class="block font-medium text-gray-700">End Time</label>
                            <input type="time" name="schedules[{{ $day }}][end_time]"
                                class="w-full mt-1 border-gray-300 rounded-lg"
                                value="{{ old('schedules.' . $day . '.end_time') }}" required>
                            @error("schedules.$day.end_time")
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="w-1/3">
                            <label class="block font-medium text-gray-700">Status</label>
                            <select name="schedules[{{ $day }}][status]" class="w-full mt-1 border-gray-300 rounded-lg"
                                required>
                                <option value="available" {{ old('schedules.' . $day . '.status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="booked" {{ old('schedules.' . $day . '.status') == 'booked' ? 'selected' : '' }}>Booked</option>
                                <option value="unavailable" {{ old('schedules.' . $day . '.status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                            @error("schedules.$day.status")
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="text-right">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Save Schedule
                </button>
            </div>
        </form>
    </div>
</x-app-layout>