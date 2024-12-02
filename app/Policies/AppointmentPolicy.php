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

    public function index(User $user, Appointment $appointment): bool
    {
        // Allow only users with the 'admin' role
        return $user->role_id === 1 || $user->id = $appointment->patient->user_id;
    }

    public function show(User $user, Appointment $appointment): bool
    {
        // Allow only users with the 'admin' role
        return $user->role_id === 1 || $user->id = $appointment->patient->user_id;
    }

    public function update(User $user, Appointment $appointment): bool
    {
        // Allow only users with the 'admin' role
        return $user->role_id === 1;
    }

    public function destroy(User $user, Appointment $appointment): bool
    {
        // Allow only users with the 'admin' role
        return $user->role_id === 1 || $user->id = $appointment->patient->user_id;
    }
}
