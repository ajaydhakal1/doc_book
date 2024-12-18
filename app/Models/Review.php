<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'appointment_id',
        'comment',
        'pdf'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
