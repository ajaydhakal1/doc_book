<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-center text-2xl font-semibold text-gray-800 mb-8">Choose Speciality</h1>

            <!-- Speciality Selection Form -->
            <form action="{{ route('appointments.create') }}" method="get" class="space-y-6">
                @csrf

                <!-- Speciality Field -->
                <div>
                    <label for="speciality_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Speciality
                    </label>
                    <select id="speciality_id" name="speciality_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out">
                        <option value="" disabled selected>Select a speciality</option>
                        @foreach ($specialities as $speciality)
                            <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                        @endforeach
                    </select>
                    @error('speciality_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-500 text-white font-medium py-2 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 transition duration-200 ease-in-out">
                        Choose
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>