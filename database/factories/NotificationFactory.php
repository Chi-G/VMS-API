<?php
namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'admin_id' => \App\Models\Admin::factory(),
            'type' => $this->faker->word,
            'data' => $this->faker->sentence,
            'read' => false,
        ];
    }
}
