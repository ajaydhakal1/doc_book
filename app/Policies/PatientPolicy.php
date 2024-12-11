<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user, Patient $patient){
        return !$user || $user->isAdmin();
    }

    public function index(User $user,Patient $patient){
        return $user->isAdmin();
    }

    public function show(User $user,Patient $patient){
        return $user->isAdmin() || $user->id === $patient->user_id;
    }

    public function update(User $user,Patient $patient){
        return $user->isAdmin() || $user->id === $patient->user->id;
    }

    public function destroy(User $user,Patient $patient){
        return $user->isAdmin() || $user->id === $patient->user->id;
    }
}
