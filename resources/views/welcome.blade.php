<x-user-layout>
    <x-slot name="title">Home Page</x-slot>
    <style>
        .hero-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 0 0 3rem 3rem;
        }

        .card {
            transition: transform 0.2s ease-in-out;
            border-radius: 1rem;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .action-card {
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .btn-modern {
            padding: 0.8rem 2rem;
            border-radius: 2rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .register-section {
            background-color: #f8f9fa;
            border-radius: 2rem;
            margin: 2rem 0;
            padding: 3rem 0;
        }

        .img-container {
            padding: 2rem;
        }
    </style>

    <x-slot name="main">
        <!-- Hero Section -->
        <section class="hero-section py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary mb-3">
                        Welcome to the <br><span class="text-dark">Appointment Management System</span>
                    </h1>
                    <p class="fs-5 text-secondary mb-4">
                        Manage your appointments with ease and efficiency
                    </p>
                    <div class="d-flex justify-content-center py-4">
                        <img src="doctor-patient.png" alt="doctor-patient" class="img-fluid rounded-4 shadow-lg">
                    </div>
                </div>
            </div>
        </section>

        @if (Auth::user())
            <!-- Action Cards Section -->
            <section class="py-5">
                <div class="container">
                    <div class="row g-4 justify-content-center">
                        <!-- Book Appointment Card -->
                        @can('create appointments')
                            <div class="col-md-6 col-lg-5">
                                <div class="card shadow-lg border-0 action-card">
                                    <div class="card-body text-center p-5">
                                        <div class="mb-4">
                                            <i class="fas fa-calendar-plus fa-3x text-primary"></i>
                                        </div>
                                        <h5 class="card-title fs-4 fw-bold mb-3">Book an Appointment</h5>
                                        <p class="card-text text-muted mb-4">
                                            Choose a doctor and a convenient time for your appointment
                                        </p>
                                        <a href="{{route('specialities.choose')}}" class="btn btn-primary btn-modern">
                                            <i class="fas fa-plus-circle me-2"></i>Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endcan

                        <!-- My Appointments Card -->
                        <div class="col-md-6 col-lg-5">
                            <div class="card shadow-lg border-0 action-card">
                                <div class="card-body text-center p-5">
                                    <div class="mb-4">
                                        <i class="fas fa-calendar-check fa-3x text-secondary"></i>
                                    </div>
                                    <h5 class="card-title fs-4 fw-bold mb-3">My Appointments</h5>
                                    <p class="card-text text-muted mb-4">
                                        View or manage your upcoming appointments
                                    </p>
                                    <a href="{{route('my-appointments')}}" class="btn btn-secondary btn-modern">
                                        <i class="fas fa-eye me-2"></i>View Appointments
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @else

            <!-- Registration Section -->
            <section class="register-section">
                <div class="container flex flex-column justify-between">
                    <!-- Doctor Registration -->
                    <div class="row align-items-center mb-5 g-4 px-4">
                        <div class="col-md-6 text-center text-md-start">
                            <h1 class="fw-bold mb-3 text-5xl">Are you a <span class="text-primary">Doctor</span>?</h1>
                            <p class="text-muted mb-4 text-xl">Register to manage your appointments, view patient
                                information,
                                and
                                more.</p>
                            <a href="{{route('doctors.create')}}" class="btn btn-primary btn-modern">
                                <i class="fas fa-user-md me-2"></i>Register as Doctor
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="img-container text-center">
                                <img src="doctor.png" alt="doctor" width="400px">
                            </div>
                        </div>
                    </div>

                    <!-- Patient Registration -->
                    <div class="row align-items-center g-4 px-4">
                        <div class="col-md-6 order-md-2 text-center text-md-start">
                            <h2 class="fw-bold mb-3 text-5xl">Are you a <span class="text-primary">Patient</span>?</h2>
                            <p class="text-muted mb-4 text-xl">Register to create your appointments, view doctor
                                information,
                                and
                                more.</p>
                            <a href="{{route('patients.create')}}" class="btn btn-primary btn-modern">
                                <i class="fas fa-user me-2"></i>Register as Patient
                            </a>
                        </div>
                        <div class="col-md-6 order-md-1">
                            <div class="img-container text-center">
                                <img src="patient.png" alt="patient" width="400px">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Support Section -->
            <section class="py-5">
                <div class="container">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-headset fa-3x text-secondary"></i>
                        </div>
                        <p class="text-muted">
                            Need assistance? Contact our support team for help with booking or managing your
                            appointments.
                        </p>
                    </div>
                </div>
            </section>
        @endif
    </x-slot>
</x-user-layout>
<x-footer></x-footer>