<footer class="bg-gray-900 border-t border-gray-800 py-12">
    <div class="container mx-auto px-4">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div>
                <h3 class="text-lg font-bold mb-4 text-white">Appointment Management</h3>
                <p class="text-gray-400 text-sm">
                    Making healthcare accessible through efficient appointment management solutions.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-4 text-white">Quick Links</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('doctors.index') }}"
                            class="text-gray-400 text-sm hover:text-blue-400 transition-colors duration-200 flex items-center">
                            <i class="fas fa-user-md mr-2"></i> Find Doctors
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('appointments.create') }}"
                            class="text-gray-400 text-sm hover:text-blue-400 transition-colors duration-200 flex items-center">
                            <i class="fas fa-calendar-check mr-2"></i> Book Appointment
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-400 text-sm hover:text-blue-400 transition-colors duration-200 flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i> My Dashboard
                        </a>
                    </li>
                    <li>
                        <a href=""
                            class="text-gray-400 text-sm hover:text-blue-400 transition-colors duration-200 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i> Health Articles
                        </a>
                    </li>
                </ul>
            </div>

            <!-- For Doctors -->
            <div>
                <h3 class="text-lg font-bold mb-4 text-white">For Doctors</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('doctors.create') }}"
                            class="text-gray-400 text-sm hover:text-blue-400 transition-colors duration-200 flex items-center">
                            <i class="fas fa-user-plus mr-2"></i> Doctor Registration
                        </a>
                    </li>
                    <li>
                        <a href=""
                            class="text-gray-400 text-sm hover:text-blue-400 transition-colors duration-200 flex items-center">
                            <i class="fas fa-user-cog mr-2"></i> Doctor Dashboard
                        </a>
                    </li>
                    <li>
                        <a href=""
                            class="text-gray-400 text-sm hover:text-blue-400 transition-colors duration-200 flex items-center">
                            <i class="fas fa-briefcase-medical mr-2"></i> Practice Management
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold mb-4 text-white">Contact Us</h3>
                <ul class="space-y-3">
                    <li>
                        <div class="flex items-center text-gray-400 text-sm">
                            <i class="fas fa-envelope mr-2"></i>
                            <span>ajaydhakal110@gmail.com</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center text-gray-400 text-sm">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Pokhara</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="mt-12 pt-8 border-t border-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="text-center md:text-left">
                    <p class="text-gray-400 text-sm">
                        © {{ date('Y') }} Appointment Management System. All rights reserved.
                    </p>
                </div>
                <div class="flex justify-center md:justify-end space-x-6">
                    <a href=""
                        class="text-gray-400 text-sm hover:text-blue-400 transition-colors duration-200 flex items-center">
                        <i class="fas fa-shield-alt mr-2"></i> Privacy Policy
                    </a>
                    <a href=""
                        class="text-gray-400 text-sm hover:text-blue-400 transition-colors duration-200 flex items-center">
                        <i class="fas fa-file-contract mr-2"></i> Terms of Service
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>