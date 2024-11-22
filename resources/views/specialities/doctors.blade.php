<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-12">
        <div class="max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-2xl border border-gray-700">
            <!-- Speciality Title -->
            <div class="p-6 text-center bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-2xl">
                <h1 class="text-3xl font-bold text-white">
                    Doctors Specializing in <span class="text-blue-200">{{ $speciality->name }}</span>
                </h1>
            </div>

            <!-- Doctors List -->
            <div class="p-6 space-y-6">
                @forelse ($doctors as $doctor)
                    <div
                        class="flex items-center justify-between p-4 bg-gray-700/50 rounded-lg border border-gray-700 hover:bg-gray-700 transition duration-150">
                        <!-- Doctor Information -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-200">{{ $doctor->user->name }}</h2>
                            <p class="text-sm text-gray-400">Phone: {{ $doctor->phone }}</p>
                            <p class="text-sm text-gray-400">Email: {{ $doctor->user->email }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <p class="text-lg text-red-400 font-medium">No doctors found for this speciality.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>