<x-app-layout>

    <h1 class="text-center text-white text-2xl font-bold py-6">Edit Doctor</h1>
    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('doctors.update', $doctor->user->id) }}" method="post" class="space-y-6">
                @method('PUT')
                @csrf
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

                <!-- Department Field -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <input type="text" id="department" name="department" value="{{ $doctor->department }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="status" class="block text-lg font-medium mb-2">Status</label>
                    <select id="status" name="status"
                        class="w-full md:w-1/2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
                        <option value="" disabled selected>Select a status</option>
                        @forelse ($doctor->schedules as $schedule)
                            <option value="{{ $schedule->status }}">
                                {{ $schedule->status }}
                            </option>
                        @empty
                            <option value="" disabled>No statuses available</option>
                        @endforelse
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
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