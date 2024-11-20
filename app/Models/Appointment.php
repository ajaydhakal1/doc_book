<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',  // Corrected to use patient_id
        'doctor_id',
        'disease',
        'category',
        'date',
        'time',
        'status'
    ];

    // Define the relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');  // 'patient_id' is the foreign key
    }

    // Define the relationship with Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');  // 'doctor_id' is the foreign key
    }
}
