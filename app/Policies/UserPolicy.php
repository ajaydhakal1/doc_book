<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user)
    {
        return $user->isAdmin();
    }
    public function store(User $user)
    {
        return $user->isAdmin();
    }
    public function show(User $user)
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
