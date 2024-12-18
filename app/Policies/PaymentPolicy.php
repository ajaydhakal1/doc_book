<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user, Payment $payment, Patient $patient, Doctor $doctor)
    {
        return $user->isAdmin() || $payment->appointment->doctor_id == $doctor->id || $payment->appointment->patient_id == $patient->id;
    }
    public function show(User $user, Payment $payment, Patient $patient, Doctor $doctor)
    {
        return $user->isAdmin() || $payment->appointment->doctor_id == $doctor->id || $payment->appointment->patient_id == $patient->id;
    }
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isDoctor();
    }
    public function edit(User $user)
    {
        return $user->isAdmin();
    }
    public function delete(User $user, Payment $payment)
    {
        return $user->isAdmin();
    }

    public function pay(User $user, Payment $payment, Patient $patient)
    {
        return $user->isAdmin() || $payment->appointment->patient_id == $patient->id;
    }
}
