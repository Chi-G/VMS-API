<?php

namespace Database\Factories;

use App\Models\FrontDeskUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class FrontDeskUserFactory extends Factory
{
    protected $model = FrontDeskUser::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'position' => $this->faker->jobTitle,
            'company_name' => $this->faker->company,
            'employee_id' => 'FD' . $this->faker->unique()->numberBetween(10000, 99999),
            'profile_picture' => 'profile_pictures/' . $this->faker->image('public/storage/profile_pictures', 400, 300, null, false),
        ];
    }
}
