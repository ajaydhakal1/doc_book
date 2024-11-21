<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $fillable = ['name'];

    // Define the relationship with Doctors
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
