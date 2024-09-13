<?php

namespace Database\Factories;

use App\Enums\OperationEnum;
use App\Enums\StatusEnum;
use App\Models\Acquirer;
use App\Models\Agent;
use App\Models\Fx;
use App\Models\Merchant;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => Str::uuid(),
            'merchant_id' => Merchant::factory(),
            'status' => $this->faker->randomElement(StatusEnum::cases()),
            'operation' => $this->faker->randomElement(OperationEnum::cases()),
            'payment_method' => 'CREDITCARD',
            'reference_no' => $this->faker->uuid,
            'custom_data' => $this->faker->sentence,
            'channel' => 'API',
            'acquirer_id' => Acquirer::factory(),
            'fx_id' => Fx::factory(),
            'agent_id' => Agent::factory(),
        ];
    }
}
