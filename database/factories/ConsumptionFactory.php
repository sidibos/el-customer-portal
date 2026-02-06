<?php

namespace Database\Factories;

use App\Models\Consumption;
use App\Models\Meter;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsumptionFactory extends Factory
{
    protected $model = Consumption::class;

    public function definition(): array
    {
        return [
            'meter_id' => Meter::factory(),
            'month' => now()->startOfMonth()->toDateString(),
            'consumption' => $this->faker->randomFloat(3, 10, 500),
        ];
    }
}