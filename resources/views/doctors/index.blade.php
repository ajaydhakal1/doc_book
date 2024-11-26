<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-700">
                <div class="p-6">
                    <div class="container">
                        <!-- Header Section -->
                        <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700">
                            <div
                                class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 flex justify-between items-center rounded-t-xl">
                                <h1 class="text-xl font-bold text-white">Doctors List</h1>
                                @can('create doctors')
                                    <a href="{{ route('doctors.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold shadow-sm hover:bg-blue-700 transition duration-150 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Doctor
                                    </a>
                                @endcan
                            </div>

                            <!-- Success/Error Messages -->
                            <x-message></x-message>

                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table
                                        class="min-w-full border-collapse border border-gray-700 rounded-lg overflow-hidden">
                                        <thead class="bg-gray-700/50 text-gray-200">
                                            <tr>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">#</th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">Name</th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">Email</th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">Phone</th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">Hourly Rate</th>
                                                <th class="border border-gray-700 px-4 py-3 font-semibold">Speciality
                                                </th>
                                                @canany(['edit doctors', 'delete doctors'])
                                                    <th class="border border-gray-700 px-4 py-3 font-semibold text-center">
                                                        Actions</th>
                                                @endcanany
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800">
                                            @forelse ($doctors as $doctor)
                                                <tr
                                                    class="even:bg-gray-700/30 hover:bg-gray-700/50 transition duration-150">
                                                    <td class="border border-gray-700 px-4 py-4 text-gray-300">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                        <div class="flex items-center">
                                                            <div
                                                                class="h-8 w-8 rounded-full bg-blue-900 flex items-center justify-center">
                                                                <span class="text-blue-200 font-medium text-sm">
                                                                    {{ substr($doctor->user->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-200">
                                                                    {{ $doctor->user->name }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                        {{ $doctor->user->email }}
                                                    </td>
                                                    <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                        {{ $doctor->phone }}
                                                    </td>
                                                    <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                       Rs.{{ $doctor->hourly_rate }}
                                                    </td>
                                                    <td class="border border-gray-700 px-4 py-3 text-gray-300">
                                                        {{ $doctor->speciality->name }}
                                                    </td>

                                                    @canany(['edit doctors', 'delete doctors'])
                                                        <td class="border border-gray-700 px-4 py-3">
                                                            <div class="flex justify-center gap-3">
                                                                @can('edit doctors')
                                                                    <a href="{{ route('doctors.edit', $doctor->id) }}"
                                                                        class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-blue-400 rounded-lg hover:bg-blue-900/50 transition-colors duration-200">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                        Edit
                                                                    </a>
                                                                @endcan
                                                                @can('delete doctors')
                                                                    <form action="{{ route('doctors.destroy', $doctor->id) }}"
                                                                        method="POST" class="inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="inline-flex items-center px-3 py-1.5 border border-red-500 text-red-400 rounded-lg hover:bg-red-900/50 transition-colors duration-200">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="h-4 w-4 mr-1.5" fill="none"
                                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                            </svg>
                                                                            Delete
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    @endcanany
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center text-red-400 px-4 py-3" colspan="6">
                                                        No records found!
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-6">
                                    {{ $doctors->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>