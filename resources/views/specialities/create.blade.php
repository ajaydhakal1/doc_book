<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gradient-to-r from-blue-600 to-blue-700 p-4 rounded-t-xl">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Specialities/Create') }}
            </h2>
            <a href="{{ route('specialities.index') }}">
                <button
                    class="py-2 px-5 bg-gray-800 text-white border border-gray-700 rounded-md hover:bg-gray-700 transition duration-150">
                    Back
                </button>
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-700">
                <div class="p-6 text-gray-300">
                    <form action="{{ route('specialities.store') }}" method="post">
                        @csrf

                        <!-- Name Field -->
                        <div class="mb-6">
                            <label for="name" class="text-lg font-medium text-gray-200">Name</label>
                            <div class="mt-3">
                                <input type="text"
                                    class="w-1/2 px-4 py-2 text-gray-900 border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    id="name" name="name" placeholder="Enter speciality name" value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <p class="text-red-500 font-medium mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150">
                            Add
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>