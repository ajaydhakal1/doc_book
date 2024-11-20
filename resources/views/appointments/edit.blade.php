<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-center text-xl font-bold mb-6">Edit Appointment</h1>
            <form action="{{ route('appointments.update', $appointment->id) }}" method="post" class="space-y-4">
                @method('PUT')
                @csrf

                <!-- Disease Field -->
                <div>
                    <label for="disease" class="block text-sm font-medium text-gray-700">Disease</label>
                    <input type="text" id="disease" name="disease" value="{{ old('disease', $appointment->disease) }}"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter disease name">
                    @error('disease')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Doctor Field -->
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor</label>
                    <select id="doctor_id" name="doctor_id"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled>Select a doctor</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category Field -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" id="category" name="category"
                        value="{{ old('category', $appointment->category) }}"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter appointment category">
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Appointment Date and Time -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Appointment Date</label>
                    <input type="date" id="date" name="date" value="{{ old('date', $appointment->date) }}"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="time" class="block text-sm font-medium text-gray-700">Appointment Time</label>
                    <input type="time" id="time" name="time" value="{{ old('time', $appointment->time) }}"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Field -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="booked" {{ old('status', $appointment->status) == 'booked' ? 'selected' : '' }}>
                            Booked</option>
                        <option value="incomplete" {{ old('status', $appointment->status) == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">
                        Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>