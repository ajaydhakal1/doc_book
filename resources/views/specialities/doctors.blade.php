<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <!-- Speciality Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center border-b pb-4">
                Doctors Specializing in <span class="text-blue-500">{{ $speciality->name }}</span>
            </h1>

            <!-- Doctors List -->
            <div class="space-y-6">
                @forelse ($doctors as $doctor)
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <!-- Doctor Information -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">{{ $doctor->user->name }}</h2>
                            <p class="text-sm text-gray-500">Phone: {{ $doctor->phone }}</p>
                            <p class="text-sm text-gray-500">Email: {{ $doctor->user->email }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <p class="text-lg text-gray-500">No doctors found for this speciality.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>