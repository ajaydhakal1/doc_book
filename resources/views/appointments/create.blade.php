<x-app-layout>
    <x-message></x-message>
    <div class="py-12 bg-gray-900">
        <div class="max-w-xl mx-auto bg-gray-800 border border-gray-700 rounded-lg shadow-xl">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-6 rounded-t-lg">
                <h1 class="text-center text-xl font-bold text-white">Create Appointment</h1>
            </div>

            <!-- Form Section -->
            <form action="{{ route('appointments.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-4">
                @csrf

                <!-- Disease Field -->
                <div>
                    <label for="disease" class="block text-sm font-medium text-gray-300">Disease</label>
                    <input type="text" id="disease" name="disease"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter disease name" value="{{ old('disease') }}">
                    @error('disease')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Doctor Selection -->
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-300">Doctor</label>
                    <select id="doctor_id" name="doctor_id"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled {{ old('doctor_id') == '' ? 'selected' : '' }}>Select a doctor</option>
                        @forelse ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }}
                            </option>
                        @empty
                            <option value="">No available doctors found</option>
                        @endforelse
                    </select>
                    @error('doctor_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Patient Selection (Admin only) -->
                @if (Auth::user()->hasRole('Admin'))
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-300">Patient</label>
                        <select id="patient_id" name="patient_id"
                            class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled {{ old('patient_id') == '' ? 'selected' : '' }}>Select a patient
                            </option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Date Field -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-300">Appointment Date</label>
                    <input type="date" id="date" name="date" min="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Fields -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-300">Start Time</label>
                    <input type="time" id="start_time" name="start_time" min="09:00" max="18:00"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-300">End Time</label>
                    <input type="time" id="end_time" name="end_time" min="09:00" max="18:00"
                        class="w-full mt-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500">
                    Create Appointment
                </button>
            </form>
        </div>
    </div>
</x-app-layout>