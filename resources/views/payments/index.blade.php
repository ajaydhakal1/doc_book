<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment List') }}
        </h2>
    </x-slot>

    <x-message></x-message>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="table-auto w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Patient Name</th>
                                <th class="px-4 py-2 text-left">Amount</th>
                                <th class="px-4 py-2 text-left">Payment Type</th>
                                <th class="px-4 py-2 text-left">Payment Status</th>
                                <th class="px-4 py-2 text-left">Created At</th>
                                <th class="px-4 py-2 text-left">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $payment->id }}</td>
                                    <td class="px-4 py-2">{{ $payment->patient->user->name }}</td>
                                    <td class="px-4 py-2">{{ $payment->amount }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($payment->payment_type) }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($payment->payment_status) }}</td>
                                    <td class="px-4 py-2">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex flex-col items-center space-y-2">
                                            <form action="{{ route('payment.delete', $payment->id) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-lg transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M20 12H4M12 4l8 8m-8 8l-8-8" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>

                                            @if (!($payment->status = 'Completed'))
                                                <a href="{{ route('payment.pay', $payment->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-lg transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Pay
                                                </a>
                                            @endif
                                        </div>
                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-2 text-center">No payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
