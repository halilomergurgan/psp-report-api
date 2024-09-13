<?php

namespace Database\Factories;

use App\Models\Fx;
use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fx>
 */
class FxFactory extends Factory
{
    protected $model = Fx::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'merchant_id' => Merchant::factory(),
            'original_amount' => $this->faker->randomFloat(2, 10, 1000),
            'original_currency' => $this->faker->currencyCode,
        ];
    }
}
