<?php

namespace Database\Factories;

use App\Models\Security;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Security>
 */
class SecurityFactory extends Factory
{
    protected $model = Security::class;
 
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'position' => $this->faker->jobTitle,
            'company_name' => $this->faker->company,
            'employee_id' => 'ST' . $this->faker->unique()->numberBetween(10000, 99999),
            'profile_picture' => 'profile_pictures/' . $this->faker->image('public/storage/profile_pictures', 400, 300, null, false),
        ];
    }
}
