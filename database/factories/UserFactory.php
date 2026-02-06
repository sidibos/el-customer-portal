<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'type' => 'authorised',
            'phone' => $this->faker->phoneNumber(),
        ];
    }

    public function primary(): static
    {
        return $this->state(fn () => ['type' => 'primary']);
    }
}