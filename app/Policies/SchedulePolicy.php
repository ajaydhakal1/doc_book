<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user, Schedule $schedule): bool
    {
        // Allow if the user is an admin or the doctor related to the schedule
        return $user->role_id === 1 || $user->role_id === 2;
    }

    public function show(User $user, Schedule $schedule): bool
    {
        // Allow if the user is an admin or the doctor related to the schedule
        return $user->role_id === 1 || $user->id === $schedule->doctor_id;
    }

    /**
     * Determine if the user can edit the schedule.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        // Allow if the user is an admin or the doctor related to the schedule
        return $user->role_id === 1 || $user->id === $schedule->doctor_id;
    }

    /**
     * Determine if the user can delete the schedule.
     */
    public function destroy(User $user, Schedule $schedule): bool
    {
        // Allow if the user is an admin or the doctor related to the schedule
        return $user->role_id === 1 || $user->id === $schedule->doctor_id;
    }
}
