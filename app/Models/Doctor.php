<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'speciality_id', // Match schema column
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with Appointment
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Define the relationship with Schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Define the relationship with Speciality
    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }
}
