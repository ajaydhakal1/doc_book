<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Xentixar\EsewaSdk\Esewa;

class PaymentController extends Controller
{
    use AuthorizesRequests;
    /**
     * View Payments
     */
    public function index(Payment $payment)
    {
        $this->authorize('index', $payment);
        $payments = Payment::all();
        return response()->json([
            'data' => $payments
        ]);
    }

    /**
     * Create Payment     */
    public function store(Request $request, Payment $payment)
    {
        $this->authorize('store', $payment);

        // Fetch the payment details from the database
        $payment = $request->payment_id;

        // dd($payment->toArray());

        $esewa = new Esewa();

        $transaction_id = 'TXN-' . uniqid();
        $payment->update(['transaction_id' => $transaction_id]);

        $esewa->config(
            route('payment.success'),
            route('payment.failure'),
            $payment->amount,
            $transaction_id
        );

        return $esewa->init();
    }

    /**
     * Show Payment
     */
    public function show(Payment $payment)
    {
        $this->authorize('show', $payment);

        $patient = $payment->patient->user->name;
        return response()->json([
            "data" => [
                "id" => $payment->id,
                "appointment_id" => $payment->appointment_id,
                "amount" => $payment->amount,
                "payment_type" => $payment->payment_type,
                "status" => $payment->payment_status
            ],
            "patient" => $patient
        ]);
    }

    /**
     * Update Payment
     */
    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $payment->update($request->all());
        return response()->json([
            "message" => "Payment Updated Successfully",
            "data" => $payment
        ]);
    }

    /**
     * Delete Payment
     */
    public function destroy(Payment $payment)
    {
        $this->authorize('destroy', $payment);

        $payment->delete();
        return response()->json([
            "message" => "Payment deleted successfully"
        ]);
    }
}
