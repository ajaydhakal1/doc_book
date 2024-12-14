<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user, Review $review, Doctor $doctor)
    {
        return $user->isAdmin() || $review->appointment->doctor_id == $doctor->id;
    }
    public function show(User $user, Doctor $doctor, Patient $patient, Review $review)
    {
        return $user->isAdmin() || $review->appointment->doctor_id == $doctor->id || $review->appointment->patient_id == $patient->id;
    }
    public function store(User $user)
    {
        return $user->isAdmin() || $user->isDoctor();
    }
    public function update(User $user, Doctor $doctor, Review $review)
    {
        return $user->isAdmin() || $review->appointment->doctor_id == $doctor->id;
    }
    public function destroy(User $user, Doctor $doctor, Review $review)
    {
        return $user->isAdmin() || $review->appointment->doctor_id == $doctor->id;
    }
}
