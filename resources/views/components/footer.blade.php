<!-- Footer Section -->
<footer class="bg-light border-top py-5">
    <div class="container">
        <!-- Main Footer Content -->
        <div class="row g-4">
            <!-- Company Info -->
            <div class="col-lg-3 col-md-6">
                <h3 class="fs-5 fw-bold mb-3">Appointment Management</h3>
                <p class="text-muted small">
                    Making healthcare accessible through efficient appointment management solutions.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6">
                <h3 class="fs-5 fw-bold mb-3">Quick Links</h3>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('doctors.index') }}"
                            class="text-decoration-none text-muted small d-flex align-items-center">
                            <i class="fas fa-user-md me-2"></i> Find Doctors
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('appointments.create') }}"
                            class="text-decoration-none text-muted small d-flex align-items-center">
                            <i class="fas fa-calendar-check me-2"></i> Book Appointment
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}"
                            class="text-decoration-none text-muted small d-flex align-items-center">
                            <i class="fas fa-tachometer-alt me-2"></i> My Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="" class="text-decoration-none text-muted small d-flex align-items-center">
                            <i class="fas fa-file-alt me-2"></i> Health Articles
                        </a>
                    </li>
                </ul>
            </div>

            <!-- For Doctors -->
            <div class="col-lg-3 col-md-6">
                <h3 class="fs-5 fw-bold mb-3">For Doctors</h3>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('doctors.create') }}"
                            class="text-decoration-none text-muted small d-flex align-items-center">
                            <i class="fas fa-user-plus me-2"></i> Doctor Registration
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="" class="text-decoration-none text-muted small d-flex align-items-center">
                            <i class="fas fa-user-cog me-2"></i> Doctor Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="" class="text-decoration-none text-muted small d-flex align-items-center">
                            <i class="fas fa-briefcase-medical me-2"></i> Practice Management
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h3 class="fs-5 fw-bold mb-3">Contact Us</h3>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fas fa-envelope me-2"></i>
                            <span>ajaydhakal110@gmail.com</span>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span>Pokhara</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="row mt-5 pt-4 border-top">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-muted small mb-3 mb-md-0">
                    © {{ date('Y') }} Appointment Management System. All rights reserved.
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-center justify-content-md-end">
                    <a href="" class="text-decoration-none text-muted small me-4 d-flex align-items-center">
                        <i class="fas fa-shield-alt me-2"></i> Privacy Policy
                    </a>
                    <a href="" class="text-decoration-none text-muted small d-flex align-items-center">
                        <i class="fas fa-file-contract me-2"></i> Terms of Service
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>