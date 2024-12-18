<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user, Appointment $appointment)
    {
        return $user->isAdmin() || $appointment->patient_id == $user->user_id;
    }
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isPatient();
    }
    public function show(User $user, Appointment $appointment)
    {
        return $user->isAdmin() || $user->id === $appointment->patient->user->id;
    }
    public function update(User $user)
    {
        return $user->isAdmin();
    }
    public function deletea(User $user, Appointment $appointment)
    {
        return $user->isAdmin() || $user->id === $appointment->patient->user_id;
    }
}
