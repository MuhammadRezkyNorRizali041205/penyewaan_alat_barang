<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'penyewaan_id' => Penyewaan::factory(),
            'user_id' => User::factory(),
            'amount' => $this->faker->numberBetween(100000, 1000000),
            'status' => 'pending',
            'transaction_id' => null,
            'payment_method' => null,
            'paid_at' => null,
        ];
    }

    public function paid(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
            'transaction_id' => $this->faker->uuid(),
            'payment_method' => $this->faker->randomElement(['transfer', 'card', 'e-wallet']),
        ]);
    }

    public function failed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
        ]);
    }
}
