<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Create a user first to associate with the doctor
        $user = User::factory()->create();

        return [
            'user_id' => $user->id, // Assign the user_id from the created user
            'phone' => $this->faker->phoneNumber(),
            'speciality_id' => 5, // You can modify this to use different specialities
            'hourly_rate' => $this->faker->numberBetween(300, 2000), // Random hourly rate
        ];
    }
}