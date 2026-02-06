<?php

namespace Database\Factories;

use App\Models\Meter;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MeterFactory extends Factory
{
    protected $model = Meter::class;

    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'meter_identifier' => 'MTR-' . strtoupper(Str::random(10)),
            'type' => $this->faker->randomElement(['gas', 'electric']),
            'is_active' => true,
        ];
    }
}