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

    public function store(User $user){
        return $user->isAdmin() || !$user;
    }

    public function show(User $user, Doctor $doctor){
        return $user->isAdmin() || $user->id === $doctor->user_id;
    }
    
    public function update(User $user, Doctor $doctor){
        return $user->isAdmin() || $user->id === $doctor->user->id;
    }
    public function destroy(User $user, Doctor $doctor){
        return $user->role_id === 1 || $user->id === $doctor->user->id;
    }
}
