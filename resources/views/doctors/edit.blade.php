<x-app-layout>
    <h1 class="text-center text-white text-2xl font-bold py-6">Edit Doctor</h1>
    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('doctors.update', $doctor->user->id) }}" method="post" class="space-y-6">
                @method('PUT')
                @csrf

                <x-message></x-message>
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" id="name" name="name" value="{{ $doctor->user->name }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ $doctor->user->email }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Phone Field -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ $doctor->phone }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Speciality Field -->
                <div>
                    <label for="speciality_id" class="block text-sm font-medium text-gray-700">Speciality</label>
                    <select id="speciality_id" name="speciality_id"
                        class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($specialities as $speciality)
                            <option value="{{ $speciality->id }}" {{ $doctor->speciality_id == $speciality->id ? 'selected' : '' }}>
                                {{ $speciality->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('speciality_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit"
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>