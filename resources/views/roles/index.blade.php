<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Roles') }}
            </h2>
            <a href="{{ route('roles.create') }}">
                <button class="py-2 px-5 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition">
                    Create Role
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm">
                        <tr class="border-b">
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Permissions</th>
                            <th class="px-6 py-3">Created</th>
                            <th class="px-6 py-3 text-center" colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                        @forelse ($roles as $role)
                            <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-3">{{ $role->id }}</td>
                                <td class="px-6 py-3">{{ $role->name }}</td>
                                <td class="px-6 py-3">
                                    {{ $role->permissions->pluck('name')->implode(', ') }}
                                </td>
                                <td class="px-6 py-3">
                                    {{ \Carbon\Carbon::parse($role->created_at)->format('d M Y') }}
                                </td>
                                @can('edit roles')
                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('roles.edit', $role->id) }}">
                                        <button
                                            class="py-2 px-4 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition">
                                            Edit
                                        </button>
                                    </a>
                                </td>
                                @endcan
                                @can('delete roles')
                                <td class="px-6 py-3 text-center">
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded-md transition">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-3 text-center text-gray-500">
                                    No roles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>