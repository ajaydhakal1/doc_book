<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-center text-xl font-bold mb-6">Create Appointment</h1>
            <form action="{{ route('appointments.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <!-- Disease Field -->
                <div>
                    <label for="disease" class="block text-sm font-medium text-gray-700">Disease</label>
                    <input type="text" id="disease" name="disease"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter disease name" value="{{ old('disease') }}">
                    @error('disease')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Disease Category
                        (Optional)</label>
                    <input type="text" id="category" name="category"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter disease category (if you know)" value="{{ old('category') }}">
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Doctor Selection -->
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor</label>
                    <select id="doctor_id" name="doctor_id"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled selected>Select a doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @if(old('doctor_id') == $doctor->id) selected @endif>
                                {{ $doctor->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if (Auth::user()->hasRole('Admin'))
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                        <select id="patient_id" name="patient_id"
                            class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled {{ old('patient_id', isset($appointment) ? $appointment->patient_id : '') == '' ? 'selected' : '' }}>
                                Select a patient
                            </option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', isset($appointment) ? $appointment->patient_id : '') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif


                <!-- Date and Time Field -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Appointment
                        Date</label>
                    <input type="date" min="{{Carbon\Carbon::now()->format('Y-m-d')}}" name="date"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="time" class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input type="time" min="{{Carbon\Carbon::now()}}" name="time"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">
                    Create Appointment
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    // Add event listener to the form submit event
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        form.addEventListener('submit', function (event) {
            // Prevent default form submission
            event.preventDefault();
        });
    });
</script>