<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900">
                    <div class="container my-5">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-lg">
                            <div class="bg-blue-500 text-white p-4 flex justify-between items-center rounded-t-lg">
                                <h1 class="text-lg font-bold">Specialties List</h1>
                                <a href="{{ route('specialities.create') }}"
                                    class="bg-white text-blue-500 hover:text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                                    <i class="bi bi-plus-circle"></i> Add Specialty
                                </a>
                            </div>

                            <x-message></x-message>

                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border-collapse border border-gray-300">
                                        <thead class="bg-blue-100 text-blue-800">
                                            <tr>
                                                <th class="border border-gray-300 px-4 py-2">#</th>
                                                <th class="border border-gray-300 px-4 py-2">Specialty</th>
                                                <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($specialities as $specialty)
                                                <tr class="even:bg-gray-100">
                                                    <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $specialty->name }}</td>
                                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                                        <div class="flex justify-center gap-4">
                                                            <!-- View Doctor List -->
                                                            <a href="{{route('doctors.speciality', $specialty->id)}}"
                                                                class="text-blue-500 hover:text-blue-700 px-3 py-1 rounded-lg text-sm border border-blue-500">
                                                                <i class="bi bi-eye"></i> View Doctors
                                                            </a>
                                                            <a href="{{ route('specialities.edit', $specialty->id) }}">
                                                                <button
                                                                    class="py-2 px-4 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition">
                                                                    Edit
                                                                </button>
                                                            </a>
                                                            <form
                                                                action="{{ route('specialities.destroy', $specialty->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded-md transition">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
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