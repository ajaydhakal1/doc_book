<x-app-layout>
    <div class="container mx-auto px-4 py-8 min-h-screen bg-gray-900 text-gray-100">
        <div class="max-w-6xl mx-auto">
            <div class="bg-gray-800 shadow-2xl rounded-lg overflow-hidden border border-gray-700">
                <div
                    class="px-6 py-4 bg-gradient-to-r from-blue-900 to-indigo-900 text-white flex items-center justify-between">
                    <h1 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-calendar-alt mr-3 text-blue-400"></i>
                        My Appointments
                    </h1>
                    <a href="{{ route('specialities.choose') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Book Appointment
                    </a>
                </div>

                @if ($appointments->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-6xl text-gray-600 mb-4"></i>
                        <p class="text-xl text-gray-400">No appointments scheduled</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700">
                                <tr class="text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                    <th class="px-6 py-3">
                                        <i class="fas fa-user-md mr-2 text-blue-500"></i>
                                        {{ Auth::user()->hasRole('Patient') ? 'Doctor' : 'Patient' }}
                                    </th>
                                    <th class="px-6 py-3">
                                        <i class="fas fa-notes-medical mr-2 text-blue-500"></i>
                                        Disease/Problem
                                    </th>
                                    <th class="px-6 py-3">
                                        <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                        Date
                                    </th>
                                    <th class="px-6 py-3">
                                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                                        Time
                                    </th>
                                    <th class="px-6 py-3">
                                        <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                                        Status
                                    </th>
                                    <th class="px-6 py-3">
                                        <i class="fas fa-dollar-sign mr-2 text-blue-500"></i>
                                        Payment
                                    </th>
                                    {{-- <th class="px-6 py-3">
                                        <i class="fas fa-dollar-sign mr-2 text-blue-500"></i>
                                        Actions
                                    </th> --}}
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach ($appointments as $appointment)
                                    <tr class="hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-900 flex items-center justify-center mr-3">
                                                <i
                                                    class="fas fa-{{ Auth::user()->hasRole('Patient') ? 'user-md' : 'user' }} text-blue-400"></i>
                                            </div>
                                            {{ Auth::user()->hasRole('Patient') ? $appointment->doctor->user->name : $appointment->patient->user->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $appointment->disease }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $appointment->date }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->start_time)->format('g:i A') }}
                                            -
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->end_time)->format('g:i A') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="
                                                px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $appointment->status == 'booked'
                                                    ? 'bg-green-900 text-green-300'
                                                    : ($appointment->status == 'completed'
                                                        ? 'bg-blue-900 text-blue-300'
                                                        : 'bg-yellow-900 text-yellow-300') }}
                                            ">
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
                                                        <input type="hidden" name="amount"
                                                            value="{{ $payment->amount }}">
                                                        <button type="submit"
                                                            class="bg-green-700 text-white px-4 py-2 rounded-lg text-xs hover:bg-green-600 transition">
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
                                        {{-- <td class="px-6 py-4">
                                            <form action="{{ route('myAppointments.destroy', $appointment->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 transition">
                                                    <i class="fas fa-trash-alt mr-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
