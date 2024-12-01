<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $primaryKey = 'patient_id';
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'age',
        'gender',
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with Appointment (A patient can have many appointments)
    public function appointments()
    {
        return $this->hasMany(Appointment::class);  // Patient can have many appointments
    }
}
