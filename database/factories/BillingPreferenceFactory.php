<?php

namespace Database\Factories;

use App\Models\BillingPreference;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingPreferenceFactory extends Factory
{
    protected $model = BillingPreference::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'format' => 'PDF',
        ];
    }
}