<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <!-- Speciality Title -->
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                Doctors Specializing in {{ $speciality->name }}
            </h1>

            <!-- Doctors List -->
            <div class="space-y-4">
                @forelse ($doctors as $doctor)
                    <div class="p-4 border rounded-lg shadow-sm bg-gray-50 flex items-center justify-between">
                        <!-- Doctor Information -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">{{ $doctor->user->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $doctor->phone }}</p>
                        </div>
                        <!-- Action Button (Optional: Link to Doctor Details or Appointments) -->
                        <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}"
                            class="text-blue-500 font-medium hover:underline">
                            Book Appointment
                        </a>
                    </div>
                @empty
                    <p class="text-center text-gray-500">No doctors found for this speciality.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>