<?php

namespace Database\Factories;

use App\Models\Fx;
use App\Models\Merchant;
use Carbon\Carbon;
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
        $startDate = Carbon::now()->subMonth();
        $endDate = Carbon::now();
        $merchant = Merchant::find(1) ?? Merchant::factory()->create();

        return [
            'merchant_id' => $merchant->id,
            'original_amount' => $this->faker->randomFloat(2, 10, 1000),
            'original_currency' => $this->faker->randomElement(['USD', 'EUR']),
            'created_at' => $this->faker->dateTimeBetween($startDate, $endDate),
            'updated_at' => $this->faker->dateTimeBetween($startDate, $endDate),
        ];
    }
}
