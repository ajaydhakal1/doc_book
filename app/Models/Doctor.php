<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'department',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->hasMany(Patient::class);
    }
}
