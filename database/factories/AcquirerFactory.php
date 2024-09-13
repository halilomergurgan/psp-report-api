<?php

namespace Database\Factories;

use App\Models\Acquirer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Acquirer>
 */
class AcquirerFactory extends Factory
{
    protected $model = Acquirer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'code' => $this->faker->swiftBicNumber,
            'type' => $this->faker->randomElement(['CREDITCARD', 'BANKTRANSFER']),
        ];
    }
}
