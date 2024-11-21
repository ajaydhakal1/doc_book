<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900">
                    <div class="container my-5">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-lg">
                            <div class="bg-blue-500 text-white p-4 flex justify-between items-center rounded-t-lg">
                                <h1 class="text-lg font-bold">Patients List</h1>
                                @can('create patients')
                                    <a href="{{ route('patients.create') }}"
                                        class="bg-white text-blue-500 hover:text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                                        <i class="bi bi-plus-circle"></i> Add patient
                                    </a>
                                @endcan
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
                                                <th class="border border-gray-300 px-4 py-2">Created At</th>
                                                @canany(['edit doctors', 'delete doctors'])
                                                <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                                                @endcanany
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($patients as $patient)
                                                <tr class="even:bg-gray-100">
                                                    <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $patient->user->name }}
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $patient->user->email }}
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        {{ $patient->created_at->format('d M Y') }}
                                                    </td>
                                                    @canany(['edit doctors', 'delete doctors'])
                                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                                            <div class="flex justify-center gap-2">
                                                                @can('edit patients')
                                                                    <a href="{{ route('patients.edit', $patient->id) }}"
                                                                        class="text-blue-500 hover:text-blue-700 px-3 py-1 rounded-lg text-sm border border-blue-500">
                                                                        <i class="bi bi-pencil-square"></i> Edit
                                                                    </a>
                                                                @endcan

                                                                @can('delete patients')
                                                                    <form action="{{ route('patients.destroy', $patient->id) }}"
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
                                                    @endcanany
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center text-red-500 px-4 py-2" colspan="5">No records
                                                        found!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="mt-4">
                                        {{ $patients->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>