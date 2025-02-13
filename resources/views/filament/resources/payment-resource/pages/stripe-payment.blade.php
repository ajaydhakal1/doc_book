<x-filament::page>
    @php
        $payment = $this->payment;
    @endphp

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6 space-y-6">
                        <div class="space-y-2">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Payment for Order #{{ $payment->id }}
                            </h2>
                            <p class="text-lg font-medium text-gray-700 dark:text-gray-200">
                                Amount: ${{ number_format($payment->amount, 2) }}
                            </p>
                        </div>

                        @if (session('success'))
                            <div
                                class="bg-green-50 dark:bg-green-900 border-2 border-green-500 dark:border-green-600 
                                      text-green-700 dark:text-green-100 rounded-lg p-4 text-center">
                                Payment Successful!
                            </div>
                        @endif

                        <form id="checkout-form" method="POST"
                            action="{{ route('stripe.create-charge', ['payment' => $payment->id]) }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="stripeToken" id="stripe-token-id">

                            <div class="space-y-4">
                                <label for="card-element"
                                    class="block text-lg font-medium text-gray-700 dark:text-gray-200">
                                    Card Details
                                </label>

                                <div id="card-element"
                                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                            bg-white dark:bg-gray-700 p-4 shadow-sm">
                                </div>
                            </div>

                            <button type="button" id="pay-btn" onclick="createToken()"
                                class="w-full rounded-lg bg-primary-600 px-4 py-3 text-sm font-semibold text-white 
                                           shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 
                                           focus:ring-primary-600 focus:ring-offset-2 dark:bg-primary-500 
                                           dark:hover:bg-primary-400 disabled:opacity-50 disabled:cursor-not-allowed
                                           transition duration-150 ease-in-out">
                                PAY ${{ number_format($payment->amount, 2) }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        const elements = stripe.elements();

        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: document.documentElement.classList.contains('dark') ?
                        '#fff' :
                        '#32325d',
                    '::placeholder': {
                        color: document.documentElement.classList.contains('dark') ?
                            '#8e8e8e' :
                            '#aab7c4'
                    }
                }
            }
        });

        cardElement.mount('#card-element');

        function createToken() {
            const payButton = document.getElementById("pay-btn");
            payButton.disabled = true;

            stripe.createToken(cardElement)
                .then(function(result) {
                    if (result.error) {
                        payButton.disabled = false;
                        alert(result.error.message);
                        return;
                    }

                    if (result.token) {
                        document.getElementById("stripe-token-id").value = result.token.id;
                        document.getElementById('checkout-form').submit();
                    }
                })
                .catch(function(error) {
                    payButton.disabled = false;
                    alert('An error occurred. Please try again.');
                });
        }
    </script>
</x-filament::page>
