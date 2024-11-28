<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xentixar\EsewaSdk\Esewa;

class PaymentController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $patient = Patient::where('user_id', $userId)->first();

        if (Auth::user()->roles == 'patient') {
            $payments = Payment::where('patient_id', $patient->id)
                ->with(['patient', 'appointment'])->paginate(5);
            $data['payments'] = $payments;
        }
        $data['payments'] = Payment::with(['patient', 'appointment'])->paginate(5);
        return view('payments.index', $data);
    }


    public function pay(Request $request, $paymentId)
    {
        // Fetch the payment details from the database
        $payment = Payment::findOrFail($paymentId);

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


    public function success(Request $request)
    {
        // Decode the eSewa response
        $esewa = new Esewa();
        $response = $esewa->decode();

        // dd($response);

        if ($response) {
            // Check if transaction_uuid is present in the response
            if (isset($response['transaction_uuid'])) {
                $transactionUuid = $response['transaction_uuid'];

                // Find the payment record in the database
                $payment = Payment::where('transaction_id', $transactionUuid)->first();

                if ($payment) {
                    // Update the payment status to 'success'
                    $payment->update([
                        'payment_status' => 'completed',
                        'payment_type' => 'online',
                    ]);

                    return redirect()->route('payments.index')->with('message', 'Payment successful!');
                }

                return redirect()->route('payments.index')->with('error', 'Payment record not found!');
            }

            return redirect()->route('payments.index')->with('error', 'Invalid response from eSewa!');
        }

    }



    public function failure(Request $request)
    {
        // Handle payment failure
        return redirect()->route('payments.index')->with('error', 'Payment failed!');
    }

    public function delete(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('payments.index')->with('message', 'Payment deleted successfully!');
    }
}