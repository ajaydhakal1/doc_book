<x-app-layout>
    <div class="py-12 bg-gray-900">
        <div class="max-w-xl mx-auto bg-gray-800 border border-gray-700 rounded-lg shadow-xl">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-6 rounded-t-lg">
                <h1 class="text-center text-2xl font-bold text-white">Choose Speciality</h1>
            </div>

            <!-- Speciality Selection Form -->
            <form action="{{ route('appointments.create') }}" method="get" class="p-6 space-y-6">
                @csrf

                <!-- Speciality Field -->
                <div>
                    <label for="speciality_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Select Speciality
                    </label>
                    <select id="speciality_id" name="speciality_id"
                        class="w-full border border-gray-700 bg-gray-800 text-white rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out">
                        <option value="" disabled selected class="text-gray-500">Select a speciality</option>
                        @foreach ($specialities as $speciality)
                            <option value="{{ $speciality->id }}" class="text-white">
                                {{ $speciality->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('speciality_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-medium py-2 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500 transition duration-200 ease-in-out">
                        Choose
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>