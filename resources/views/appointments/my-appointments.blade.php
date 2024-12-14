<x-app-layout>
    <div class="container mx-auto px-4 py-8 min-h-screen bg-gray-900 text-gray-100">
        <x-message></x-message>

        <div class="max-w-6xl mx-auto">
            <div class="bg-gray-800 shadow-2xl rounded-lg overflow-hidden border border-gray-700">
                <!-- Header -->
                <div
                    class="px-6 py-4 bg-gradient-to-r from-blue-900 to-indigo-900 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-calendar-alt mr-3 text-blue-400"></i>
                        My Appointments
                    </h1>
                    <a href="{{ route('specialities.choose') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition flex items-center shadow">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Book Appointment
                    </a>
                </div>

                <!-- No Appointments -->
                @if ($appointments->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-6xl text-gray-600 mb-4"></i>
                        <p class="text-xl text-gray-400">No appointments scheduled</p>
                    </div>
                @else
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-gray-300">
                            <thead class="bg-gray-700 text-gray-400">
                                <tr class="text-left text-xs font-semibold uppercase">
                                    <th class="px-6 py-3">Doctor/Patient</th>
                                    <th class="px-6 py-3">Disease/Problem</th>
                                    <th class="px-6 py-3">Date</th>
                                    <th class="px-6 py-3">Time</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Payment</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach ($appointments as $appointment)
                                    <tr class="hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-900 flex items-center justify-center mr-3">
                                                <i
                                                    class="fas fa-{{ Auth::user()->hasRole('Patient') ? 'user-md' : 'user' }} text-blue-400"></i>
                                            </div>
                                            {{ Auth::user()->hasRole('Patient') ? $appointment->doctor->user->name : $appointment->patient->user->name }}
                                        </td>
                                        <td class="px-6 py-4">{{ $appointment->disease }}</td>
                                        <td class="px-6 py-4">{{ $appointment->date }}</td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $appointment->status === 'booked' ? 'bg-green-900 text-green-300' : ($appointment->status === 'completed' ? 'bg-blue-900 text-blue-300' : 'bg-yellow-900 text-yellow-300') }}">
                                                {{ ucfirst($appointment->status ?? 'unknown') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $payment = $payments->firstWhere('appointment_id', $appointment->id);
                                            @endphp
                                            @if ($appointment->status === 'completed' && $payment)
                                                @if ($payment->payment_status === 'pending')
                                                    <form action="{{ route('payment.pay', $payment->id) }}"
                                                        method="get">
                                                        @csrf
                                                        <button type="submit"
                                                            class="bg-green-700 text-white px-4 py-2 rounded-lg text-xs hover:bg-green-600 transition shadow">
                                                            <i class="fas fa-credit-card mr-1"></i>
                                                            Pay
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-green-400 font-semibold">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Paid
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $review = $reviews->firstWhere('appointment_id', $appointment->id);
                                            @endphp

                                            @if ($appointment->status === 'completed' && Auth::user()->can('give reviews'))
                                                @if ($review)
                                                    <a href="{{ route('reviews.show', $review->id) }}"
                                                        class="bg-green-700 text-white px-4 py-2 rounded-lg text-xs hover:bg-green-600 transition shadow">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        View Review
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0)"
                                                        class="bg-blue-700 text-white px-4 py-2 rounded-lg text-xs hover:bg-blue-600 transition give-review-button shadow"
                                                        data-appointment-id="{{ $appointment->id }}">
                                                        <i class="fas fa-star mr-1"></i>
                                                        Give Review
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-gray-800 text-gray-100 rounded-lg w-1/2 h-1/2 p-6 shadow-lg flex flex-col justify-between">
            <h2 class="text-xl font-bold mb-4">Give Review</h2>
            <form id="reviewForm" action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data"
                class="flex-1 flex flex-col justify-between">
                @csrf
                <input type="hidden" name="appointment_id" id="appointment_id">
                <div class="mb-4">
                    <label for="comments" class="block text-sm font-semibold mb-2">Comment</label>
                    <textarea name="comment" id="comment" rows="4"
                        class="w-full p-2 rounded bg-gray-900 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label for="pdf" class="block text-sm font-semibold mb-2">Upload PDF</label>
                    <input type="file" name="pdf" id="pdf"
                        class="w-full p-2 bg-gray-900 border border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="button" id="cancelButton"
                        class="bg-red-600 text-white px-4 py-2 rounded mr-2 hover:bg-red-500 transition shadow">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 transition shadow">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('reviewModal');
            const cancelButton = document.getElementById('cancelButton');
            const giveReviewButtons = document.querySelectorAll('.give-review-button');
            const appointmentIdField = document.getElementById('appointment_id');

            // Ensure modal is hidden on page load
            modal.classList.add('hidden');

            // Show the modal when "Give Review" is clicked
            giveReviewButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const appointmentId = button.getAttribute('data-appointment-id');
                    appointmentIdField.value = appointmentId;
                    modal.classList.remove('hidden');
                });
            });

            // Hide the modal when "Cancel" is clicked
            cancelButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>
