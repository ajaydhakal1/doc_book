<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Roles / Edit') }}
            </h2>
            <a href="{{ route('roles.index') }}">
                <button class="py-2 px-5 bg-gray-300 hover:bg-gray-400 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md transition">
                    Back
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Role Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-lg font-medium mb-2">Role Name</label>
                            <input 
                                type="text" 
                                class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm w-full md:w-1/2 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none" 
                                id="name" 
                                name="name" 
                                placeholder="Enter role name"
                                value="{{ old('name', $role->name) }}">

                            @error('name')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div class="mb-6">
                            <h3 class="font-medium text-lg mb-4">Assign Permissions</h3>

                            <!-- Select All Button -->
                            <div class="mb-4">
                                <button type="button" id="select-all" class="bg-blue-500 text-white px-4 py-2 rounded-lg focus:ring-4 focus:ring-blue-300">
                                    Select All
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($permissions as $permission)
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            name="permission[]" 
                                            class="rounded text-blue-500 focus:ring focus:ring-blue-500 permission-checkbox" 
                                            id="permission-{{ $permission->id }}" 
                                            value="{{ $permission->name }}"
                                            {{ $hasPermissions->contains($permission->name) ? 'checked' : '' }}>
                                        <label for="permission-{{ $permission->id }}" class="ml-2 text-sm dark:text-gray-300">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md shadow-sm transition">
                                Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Select All Button -->
    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const isChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !isChecked;
            });
        });
    </script>
</x-app-layout>
