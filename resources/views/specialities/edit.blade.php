<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Speciality / Edit') }}
            </h2>
            <a href="{{ route('specialities.index') }}">
                <button
                    class="py-2 px-5 bg-gray-300 hover:bg-gray-400 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md transition">
                    Back
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('specialities.update', $speciality->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Speciality Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-lg font-medium mb-2">Speciality Name</label>
                            <input type="text"
                                class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm w-full md:w-1/2 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none"
                                id="name" name="name" placeholder="Enter speciality name"
                                value="{{ old('name', $speciality->name) }}">

                            @error('name')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md shadow-sm transition">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>