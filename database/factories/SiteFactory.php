<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'name' => $this->faker->city() . ' Site',
            'address' => $this->faker->address(),
        ];
    }
}