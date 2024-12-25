<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    // protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'age',
        'gender',
    ];

    // Define the relationship with User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with Appointment (A patient can have many appointments)
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
