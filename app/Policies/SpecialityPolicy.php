<?php

namespace App\Policies;

use App\Models\User;

class SpecialityPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }
    public function store(User $user)
    {
        return $user->isAdmin();
    }
    public function update(User $user)
    {
        return $user->isAdmin();
    }
    public function delete(User $user)
    {
        return $user->isAdmin();
    }
}
