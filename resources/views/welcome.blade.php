<x-app-layout>
    <!-- Hero Section -->
    <section
        class="bg-gradient-to-br from-gray-100 dark:from-gray-900 dark:to-gray-800 to-white rounded-b-[3rem] transition-colors duration-300">
        <div class="container mx-auto px-4 py-12">
            <div class="text-center mb-10">
                <h1 class="text-4xl md:text-5xl font-bold mb-3 pt-5 text-gray-900 dark:text-white">
                    Welcome to the <br>
                    <span class="text-blue-600 dark:text-blue-400">Appointment Management System</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Manage your appointments with ease and efficiency
                </p>
                <div class="flex justify-center py-8">
                    <img src="doctor-patient.png" alt="Doctor and Patient"
                        class="rounded-2xl shadow-2xl w-full max-w-6xl transition-transform">
                </div>
            </div>
        </div>
    </section>

    @auth
        <!-- Action Cards Section -->
        <section class="py-12 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    <!-- Book Appointment Card -->
                    @can('create appointments')
                        <div class="transform hover:-translate-y-2 transition-all duration-300">
                            <div
                                class="
                                bg-white dark:bg-gray-700 
                                rounded-2xl shadow-lg hover:shadow-xl 
                                p-8 min-h-[250px] 
                                flex flex-col items-center justify-center
                                border border-gray-100 dark:border-gray-600
                            ">
                                <div class="mb-6">
                                    <i class="fas fa-calendar-plus text-4xl text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <h5 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">
                                    Book an Appointment
                                </h5>
                                <p class="text-gray-600 dark:text-gray-300 mb-6 text-center">
                                    Choose a doctor and a convenient time for your appointment
                                </p>
                                <a href="{{ route('specialities.choose') }}"
                                    class="
                                    inline-flex items-center px-6 py-3 
                                    rounded-full 
                                    bg-blue-600 hover:bg-blue-700 
                                    dark:bg-blue-500 dark:hover:bg-blue-600 
                                    text-white 
                                    font-medium uppercase tracking-wider 
                                    text-sm transition-colors duration-200
                                ">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    @endcan

                    <!-- My Appointments Card -->
                    <div class="transform hover:-translate-y-2 transition-all duration-300">
                        <div
                            class="
                            bg-white dark:bg-gray-700 
                            rounded-2xl shadow-lg hover:shadow-xl 
                            p-8 min-h-[250px] 
                            flex flex-col items-center justify-center
                            border border-gray-100 dark:border-gray-600
                        ">
                            <div class="mb-6">
                                <i class="fas fa-calendar-check text-4xl text-gray-500 dark:text-gray-300"></i>
                            </div>
                            <h5 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">
                                My Appointments
                            </h5>
                            <p class="text-gray-600 dark:text-gray-300 mb-6 text-center">
                                View or manage your upcoming appointments
                            </p>
                            <a href="{{ route('my-appointments') }}"
                                class="
                                inline-flex items-center px-6 py-3 
                                rounded-full 
                                bg-gray-600 hover:bg-gray-700 
                                dark:bg-gray-500 dark:hover:bg-gray-600 
                                text-white 
                                font-medium uppercase tracking-wider 
                                text-sm transition-colors duration-200
                            ">
                                <i class="fas fa-eye mr-2"></i>
                                View Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <!-- Registration Section -->
        <section class="bg-gray-100 dark:bg-gray-900 py-16 my-8 rounded-3xl transition-colors duration-300">
            <div class="container mx-auto px-4">
                <!-- Doctor Registration -->
                <div class="grid md:grid-cols-2 gap-8 items-center mb-16 px-8">
                    <div class="text-center md:text-left">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900 dark:text-white">
                            Are you a <span class="text-blue-600 dark:text-blue-400">Doctor</span>?
                        </h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-6 text-xl">
                            Register to manage your appointments, view patient information, and more.
                        </p>
                        <a href="{{ route('doctors.create') }}"
                            class="
                            inline-flex items-center px-6 py-3 
                            rounded-full 
                            bg-blue-600 hover:bg-blue-700 
                            dark:bg-blue-500 dark:hover:bg-blue-600 
                            text-white 
                            font-medium uppercase tracking-wider 
                            text-sm transition-colors duration-200
                        ">
                            <i class="fas fa-user-md mr-2"></i>
                            Register as Doctor
                        </a>
                    </div>
                    <div class="flex justify-center p-8">
                        <img src="{{ ('doctor.png') }}" alt="Doctor"
                            class="w-full max-w-md transition-transform hover:scale-105">
                    </div>
                </div>

                <!-- Patient Registration -->
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div class="order-2 md:order-1 flex justify-center p-8">
                        <img src="{{ ('patient.png') }}" alt="Patient"
                            class="w-full max-w-md transition-transform hover:scale-105">
                    </div>
                    <div class="order-1 md:order-2 text-center md:text-left">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900 dark:text-white">
                            Are you a <span class="text-blue-600 dark:text-blue-400">Patient</span>?
                        </h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-6 text-xl">
                            Register to create your appointments, view doctor information, and more.
                        </p>
                        <a href="{{ route('patients.create') }}"
                            class="
                            inline-flex items-center px-6 py-3 
                            rounded-full 
                            bg-blue-600 hover:bg-blue-700 
                            dark:bg-blue-500 dark:hover:bg-blue-600 
                            text-white 
                            font-medium uppercase tracking-wider 
                            text-sm transition-colors duration-200
                        ">
                            <i class="fas fa-user mr-2"></i>
                            Register as Patient
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Support Section -->
        <section class="py-12 bg-white dark:bg-gray-800 transition-colors duration-300">
            <div class="container mx-auto px-4">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="fas fa-headset text-4xl text-gray-500 dark:text-gray-300"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Need assistance? Contact our support team for help with booking or managing your appointments.
                    </p>
                </div>
            </div>
        </section>
    @endauth
</x-app-layout>

<x-footer></x-footer>
