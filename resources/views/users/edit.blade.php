<x-app-layout>

    <h1 class="text-center text-white text-2xl font-bold py-6">Edit User</h1>
    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('users.update', $user->id) }}" method="post" class="space-y-6">
                @method('PUT')
                @csrf
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Role Field -->
                <div>
                    <label for="role" class="block text-lg font-medium mb-2">Role</label>
                    <select id="role" name="role"
                        class="w-full md:w-1/2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
                        <option value="" disabled>Select a role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @if ($user->hasRole($role->name)) selected @endif>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
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