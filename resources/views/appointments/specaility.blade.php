<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Create Appointment</h1>

        <x-message></x-message>

        <form action="{{ route('appointments.create') }}" method="get">
            @csrf

            <div class="mb-4">
                <label for="specialty" class="block font-medium text-gray-700">Select Specialty</label>
                <select id="specialty" name="specialty" required class="w-full mt-1 border-gray-300 rounded-lg">
                    <option value="" disabled selected>Select a specialty</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty }}">{{ $specialty }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Additional fields for date and time go here -->

            <div class="text-right">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Next
                </button>
            </div>
        </form>
    </div>
</x-app-layout>