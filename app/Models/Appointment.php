<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'disease',
        'category',
        'appointment_datetime',
    ];


    protected $casts = [
        'appointment_datetime' => 'datetime',  // Cast to Carbon instance
    ];

    public function user()
    {
        return $this->belongsTo(User::class);  // Assuming the 'appointments' table has a 'user_id' column
    }

    // Relationship with Doctor (assuming you have a 'doctors' table and a 'doctor_id' column in 'appointments' table)
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);  // Assuming the 'appointments' table has a 'doctor_id' column
    }
}
