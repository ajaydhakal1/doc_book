<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'amount',
        'payment_type',
        'payment_status',
        'transaction_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

}
