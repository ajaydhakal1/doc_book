<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{

    use HasFactory;
    protected $fillable = [
        'user_id',
        'phone',
        'speciality_id',
        'hourly_rate',
    ];

    // Define the relationship with User
    public function user(): BelongsTo
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
