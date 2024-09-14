<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'number' => $this->faker->creditCardNumber,
            'expiry_month' => $this->faker->month,
            'expiry_year' => $this->faker->year,
            'email' => $this->faker->email,
            'billing_first_name' => $this->faker->firstName,
            'billing_last_name' => $this->faker->lastName,
            'billing_address1' => $this->faker->address,
            'billing_city' => $this->faker->city,
            'billing_postcode' => $this->faker->postcode,
            'billing_country' => $this->faker->countryCode,
            'shipping_first_name' => $this->faker->firstName,
            'shipping_last_name' => $this->faker->lastName,
            'shipping_address1' => $this->faker->address,
            'shipping_city' => $this->faker->city,
            'shipping_postcode' => $this->faker->postcode,
            'shipping_country' => $this->faker->countryCode,
        ];
    }
}
