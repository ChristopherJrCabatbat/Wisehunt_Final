<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name,
            'contact_num' => $this->faker->phoneNumber,
            'product_name' => $this->faker->word,
            'qty' => $this->faker->randomDigitNotNull, // Changed from randomDigit(1000)
            'selling_price' => $this->faker->randomFloat(2, 10, 1000),
            'total_price' => $this->faker->randomFloat(2, 10, 1000),
            'amount_tendered' => $this->faker->randomFloat(2, 10, 1000),
            'change_due' => $this->faker->randomFloat(2, 10, 1000),
            'profit' => $this->faker->randomFloat(2, 10, 1000),
            'date' => $this->faker->dateTimeThisMonth, // Random date within the current month
        ];
    }
}
