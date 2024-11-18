<x-user-layout>
    <x-slot name="title">Dashboard Page</x-slot>
    <x-slot name="main">
        <div class="container mt-5">
            <div class="text-center mb-4">
                <h1 class="display-5">Welcome to the Appointment Management System</h1>
                <p class="text-muted">Manage your appointments with ease and efficiency.</p>
            </div>

            <div class="row g-4">
                <!-- Book Appointment Section -->
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title">Book an Appointment</h5>
                            <p class="card-text">Choose a doctor and a convenient time for your appointment.</p>
                            <a href="" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div>

                <!-- My Appointments Section -->
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title">My Appointments</h5>
                            <p class="card-text">View or manage your upcoming appointments.</p>
                            <a href="" class="btn btn-secondary">View Appointments</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="row mt-5">
                <div class="col text-center">
                    <p class="text-muted">Need assistance? Contact our support team for help with booking or managing
                        your appointments.</p>
                </div>
            </div>
        </div>
    </x-slot>
</x-user-layout>