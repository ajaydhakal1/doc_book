<x-user-layout>
    <x-slot name="title">Dashboard Page</x-slot>
    <x-slot name="main">
        <div class="bg-gray-100 py-12 min-h-screen">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-extrabold text-gray-800 mb-2">
                        Welcome to the Appointment Management System
                    </h1>
                    <p class="text-lg text-gray-600">
                        Manage your appointments with ease and efficiency.
                    </p>
                </div>

                <x-message></x-message>

                <!-- Action Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Book Appointment Section -->
                    <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                        <h5 class="text-2xl font-semibold text-gray-800 mb-4">Book an Appointment</h5>
                        <p class="text-gray-600 mb-6">
                            Choose a doctor and a convenient time for your appointment.
                        </p>
                        <a href="{{route('specialities.choose')}}"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                            Book Now
                        </a>
                    </div>

                    <!-- My Appointments Section -->
                    <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                        <h5 class="text-2xl font-semibold text-gray-800 mb-4">My Appointments</h5>
                        <p class="text-gray-600 mb-6">
                            View or manage your upcoming appointments.
                        </p>
                        <a href="{{route('my-appointments')}}"
                            class="px-6 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700">
                            View Appointments
                        </a>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-12 text-center">
                    <p class="text-gray-500">
                        Need assistance? Contact our support team for help with booking or managing your appointments.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>
</x-user-layout>