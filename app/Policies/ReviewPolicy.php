<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine if the user can view a list of reviews.
     */
    public function index(User $user, Review $review)
    {
        return $user->isAdmin() || $review->appointment->doctor_id == $user->doctor->id;
    }

    /**
     * Determine if the user can view a review.
     */
    public function show(User $user, Review $review)
    {
        return $user->isAdmin() ||
            ($review->appointment->doctor_id == optional($user->doctor)->id) ||
            ($review->appointment->patient_id == optional($user->patient)->id);
    }

    /**
     * Determine if the user can create a review.
     */
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isDoctor();
    }

    /**
     * Determine if the user can edit a review.
     */
    public function edit(User $user, Review $review)
    {
        return $user->isAdmin() || $review->appointment->doctor_id == optional($user->doctor)->id;
    }

    /**
     * Determine if the user can delete a review.
     */
    public function delete(User $user, Review $review)
    {
        return $user->isAdmin() || $review->appointment->doctor_id == optional($user->doctor)->id;
    }
}
