<?php
namespace Database\Factories;

use App\Models\Visit;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition()
    {
        return [
            'admin_id' => Admin::inRandomOrder()->first()->id,
            'staff_id' => Staff::inRandomOrder()->first()->id,
            'visitor_id' => Visitor::factory(),
            'visitor_type' => $this->faker->randomElement(['individual', 'group', 'walk-in', 'VIP']),
            'purpose' => $this->faker->randomElement(['recruitment', 'business meeting', 'repairs/maintenance', 'personal/official','other']),
            'check_in' => $this->faker->dateTimeThisMonth(),
            'check_out' => $this->faker->optional()->dateTimeThisMonth(),
            'host_name' => $this->faker->name,
            'building' => $this->faker->randomElement(['building 1', 'building 2', 'building 3', 'building 4']),
            'floor' => $this->faker->randomElement(['1st floor', '2nd floor', '3rd floor', '4th floor']),
            'group_size' => $this->faker->randomElement(['2 to 10', '10 to 20', '20 to 30', '30 above']),
            'visit_time' => $this->faker->randomElement(['30mins', '1 to 2hrs', '3 to 4hrs', '4 to 5hrs']),
            'status' => $this->faker->randomElement(['scheduled', 'pre-scheduled', 'cancelled', 'pending']),
        ];
    }
}
