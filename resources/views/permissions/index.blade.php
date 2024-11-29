<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Permissions') }}
            </h2>
            <a href="{{ route('permissions.create') }}">
                <button class="py-2 px-5 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition">
                    Create
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-left border-collapse">
                    <!-- Table Head -->
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm">
                        <tr class="border-b dark:border-gray-600">
                            <th class="px-6 py-3" width="60">#</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3" width="180">Created</th>
                            <th class="px-6 py-3 text-center" colspan="2" width="120">Actions</th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                        @if ($permissions->isNotEmpty())
                            @foreach ($permissions as $permission)
                                <tr class="border-b dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-3">{{ $permission->id }}</td>
                                    <td class="px-6 py-3">{{ $permission->name }}</td>
                                    <td class="px-6 py-3">
                                        {{ \Carbon\Carbon::parse($permission->created_at)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <a href="{{ route('permissions.edit', $permission->id) }}">
                                            <button
                                                class="py-2 px-4 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition">
                                                Edit
                                            </button>
                                        </a>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <form action="{{ route('permissions.destroy', $permission->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded-md transition">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-6 py-3 text-center text-gray-500 dark:text-gray-400">
                                    No permissions found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="p-4">
                    {{ $permissions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
