<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'department',
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with Appointment (A doctor can have many appointments)
    public function appointments()
    {
        return $this->hasMany(Appointment::class);  // A doctor can have many appointments
    }

    public function schedules(){
        return $this->hasMany(Schedule::class);
    }
}
