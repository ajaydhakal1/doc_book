<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900">
                    <div class="container my-5">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-lg">
                            <div class="bg-blue-500 text-white p-4 flex justify-between items-center rounded-t-lg">
                                <h1 class="text-lg font-bold">Users List</h1>
                                <a href="{{ route('users.create') }}"
                                    class="bg-white text-blue-500 hover:text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                                    <i class="bi bi-plus-circle"></i> Add User
                                </a>
                            </div>

                            <x-message></x-message>

                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border-collapse border border-gray-300">
                                        <thead class="bg-blue-100 text-blue-800">
                                            <tr>
                                                <th class="border border-gray-300 px-4 py-2">#</th>
                                                <th class="border border-gray-300 px-4 py-2">Name</th>
                                                <th class="border border-gray-300 px-4 py-2">Email</th>
                                                <th class="border border-gray-300 px-4 py-2">Role</th>
                                                <th class="border border-gray-300 px-4 py-2">Created At</th>
                                                <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $user)
                                                <tr class="even:bg-gray-100">
                                                    <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        {{$user->roles->pluck('name')->implode('')}}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $user->created_at }}
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                                        <div class="flex justify-center gap-2">
                                                            @can('edit users')
                                                            <a href="{{ route('users.edit', $user->id) }}"
                                                                class="text-blue-500 hover:text-blue-700 px-3 py-1 rounded-lg text-sm border border-blue-500">
                                                                <i class="bi bi-pencil-square"></i> Edit
                                                            </a>
                                                            @endcan
                                                            @can('delete users')
                                                            <form action="{{ route('users.destroy', $user->id) }}"
                                                                method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-500 hover:text-red-700 px-3 py-1 rounded-lg text-sm border border-red-500">
                                                                    <i class="bi bi-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center text-red-500 px-4 py-2" colspan="5">No records
                                                        found!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>