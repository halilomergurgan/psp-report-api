<?php

namespace Database\Factories;

use App\Enums\OperationEnum;
use App\Enums\StatusEnum;
use App\Models\Acquirer;
use App\Models\Agent;
use App\Models\Fx;
use App\Models\Merchant;
use App\Models\Transaction;
use Carbon\Carbon;
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
        $startDate = Carbon::now()->subMonth();
        $endDate = Carbon::now();

        $merchant = Merchant::find(1) ?? Merchant::factory()->create();
        $acquirer = Acquirer::find(1) ?? Acquirer::factory()->create();
        $agent = Agent::find(1) ?? Agent::factory()->create();

        $fxCurrency = $this->faker->randomElement(['USD', 'EUR']);
        $fx = Fx::create([
            'merchant_id' => $merchant->id,
            'original_amount' => $this->faker->randomFloat(2, 100, 1000),
            'original_currency' => $fxCurrency,
            'created_at' => $this->faker->dateTimeBetween($startDate, $endDate),
            'updated_at' => $this->faker->dateTimeBetween($startDate, $endDate),
        ]);

        return [
            'transaction_id' => Str::uuid(),
            'merchant_id' => $merchant->id,
            'status' => $this->faker->randomElement(['APPROVED', 'WAITING', 'DECLINED', 'ERROR']),
            'operation' => $this->faker->randomElement(['DIRECT', 'REFUND', '3D', '3DAUTH', 'STORED']),
            'payment_method' => 'CREDITCARD',
            'reference_no' => $this->faker->uuid,
            'custom_data' => $this->faker->sentence,
            'channel' => 'API',
            'acquirer_id' => $acquirer->id,
            'fx_id' => $fx->id,
            'agent_id' => $agent->id,
            'created_at' => $this->faker->dateTimeBetween($startDate, $endDate),
            'updated_at' => $this->faker->dateTimeBetween($startDate, $endDate),
        ];
    }
}
