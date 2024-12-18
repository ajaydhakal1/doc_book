<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;

class DoctorPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user){
        return $user->isAdmin();
    }

    public function store(User $user){
        return $user->isAdmin() || !$user;
    }

    public function show(User $user, Doctor $doctor){
        return $user->isAdmin() || $user->id === $doctor->user_id;
    }
    
    public function update(User $user, Doctor $doctor){
        return $user->isAdmin() || $user->id === $doctor->user->id;
    }
    public function delete(User $user, Doctor $doctor){
        return $user->isAdmin();
    }
}
