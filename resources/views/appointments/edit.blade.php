<x-app-layout>
    <x-message></x-message>
    <div class="py-12 bg-gray-900">
        <div class="max-w-xl mx-auto bg-gray-800 border border-gray-700 rounded-lg shadow-xl">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-6 rounded-t-lg">
                <h1 class="text-center text-xl font-bold text-white">Edit Appointment</h1>
            </div>

            <!-- Form Section -->
            <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" class="p-6 space-y-4">
                @method('PUT')
                @csrf

                <!-- Disease Field -->
                <div>
                    <label for="disease" class="block text-sm font-medium text-gray-300">Disease</label>
                    <input type="text" id="disease" name="disease" value="{{ old('disease', $appointment->disease) }}"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter disease name">
                    @error('disease')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Doctor Field -->
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-300">Doctor</label>
                    <select id="doctor_id" name="doctor_id"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled>Select a doctor</option>
                        <option value="{{ $appointment->doctor_id }}" selected>
                            {{ $appointment->doctor->user->name }}
                        </option>
                    </select>
                    @error('doctor_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Appointment Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-300">Appointment Date</label>
                    <input type="date" id="date" name="date" value="{{ old('date', $appointment->date) }}"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-300">Start Time</label>
                    <input type="time" id="start_time" name="start_time"
                        value="{{ old('start_time', $appointment->start_time) }}" min="09:00" max="18:00"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-300">End Time</label>
                    <input type="time" id="end_time" name="end_time"
                        value="{{ old('end_time', $appointment->end_time) }}" min="09:00" max="18:00"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Field -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300">Status</label>
                    <select id="status" name="status"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="booked" {{ old('status', $appointment->status) == 'booked' ? 'selected' : '' }}>
                            Booked</option>
                        <option value="failed" {{ old('status', $appointment->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500">
                        Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>