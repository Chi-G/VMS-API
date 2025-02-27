<?php
namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition()
    {
        return [
            'admin_id' => \App\Models\Admin::factory(),
            'key' => $this->faker->word,
            'value' => $this->faker->sentence,
        ];
    }
}
