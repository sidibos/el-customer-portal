<?php

namespace Database\Factories;

use App\Models\Meter;
use App\Models\MeterReading;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeterReadingFactory extends Factory
{
    protected $model = MeterReading::class;

    public function definition(): array
    {
        return [
            'meter_id' => Meter::factory(),
            'reading' => $this->faker->randomFloat(3, 100, 5000),
            'read_at' => now(),
        ];
    }
}