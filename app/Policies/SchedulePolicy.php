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
    public function index(User $user, Schedule $schedule): bool
    {
        // Allow if the user is an admin or the doctor related to the schedule
        return $user->isAdmin() || $user->id === $schedule->doctor_id;
    }

    public function create(User $user): bool
    {
        // Allow if the user is an admin or the doctor related to the schedule
        return $user->isAdmin() || $user->isDoctor();
    }

    public function show(User $user, Schedule $schedule): bool
    {
        // Allow if the user is an admin or the doctor related to the schedule
        return $user->isAdmin() || $user->id === $schedule->doctor->user_id;
    }

    /**
     * Determine if the user can edit the schedule.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        // Allow if the user is an admin or the doctor related to the schedule
        return $user->isAdmin() || $user->id === $schedule->doctor_id;
    }

    /**
     * Determine if the user can delete the schedule.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        // Allow if the user is an admin or the doctor related to the schedule
        return $user->isAdmin() || $user->id === $schedule->doctor_id;
    }
}
