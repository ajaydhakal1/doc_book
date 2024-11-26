<x-app-layout>
    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-2xl rounded-xl overflow-hidden border border-gray-700">
                <!-- Header Section -->
                <div class="p-6 bg-gradient-to-r from-blue-900 to-blue-800">
                    <div class="flex flex-wrap justify-between items-center">
                        <div class="mb-4 sm:mb-0">
                            <h1 class="text-2xl font-bold text-white">Users List</h1>
                            <p class="text-blue-200 text-sm mt-1">Manage system users and their roles</p>
                        </div>
                        <a href="{{ route('users.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold shadow-sm hover:bg-blue-700 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add User
                        </a>
                    </div>
                </div>

                <x-message></x-message>

                <!-- Filters Section -->
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-4">
                        <!-- Search -->
                        <div class="w-full sm:w-auto flex-grow">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by name or email"
                                class="w-full px-4 py-2 rounded-lg border border-gray-600 bg-gray-700 text-gray-200">
                        </div>

                        <!-- Sort By -->
                        <div class="w-full sm:w-auto flex-grow">
                            <select name="sort_by"
                                class="w-full px-4 py-2 rounded-lg border border-gray-600 bg-gray-700 text-gray-200">
                                <option value="">Sort By</option>
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name
                                </option>
                                <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email
                                </option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                                    Created At</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="w-full sm:w-auto flex-grow">
                            <select name="order"
                                class="w-full px-4 py-2 rounded-lg border border-gray-600 bg-gray-700 text-gray-200">
                                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending
                                </option>
                                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending
                                </option>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="w-full sm:w-auto">
                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table Section -->
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-800">
                            <tr>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                    #
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                    Role
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                    Created At
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 rounded-full bg-blue-900 flex items-center justify-center">
                                                <span class="text-blue-200 font-medium text-sm">
                                                    {{ substr($user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-200">{{ $user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900 text-blue-200">
                                            {{ $user->roles->pluck('name')->implode('') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-400">
                                        {{ $user->created_at }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center space-x-2">
                                            @can('edit users')
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-blue-400 rounded-lg hover:bg-blue-900/50 transition-colors duration-200">
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete users')
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 border border-red-500 text-red-400 rounded-lg hover:bg-red-900/50 transition-colors duration-200">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17v2a2 2 0 002 2h2a2 2 0 002-2v-2m-1-4h.01M4 4h16M4 8h16m-7 4h7" />
                                            </svg>
                                            <span class="font-medium text-gray-400">No users found</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-700">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
