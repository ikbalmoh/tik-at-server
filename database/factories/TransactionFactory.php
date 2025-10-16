<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $grandTotal = fake()->randomFloat(2, 10000, 100000);
        $pay = fake()->randomFloat(2, $grandTotal, $grandTotal * 2);
        $charge = $grandTotal - $pay;

        $purchaseDate = fake()->dateTimeBetween('-3 months');

        $user = User::first();

        return [
            'user_id' => $user->id,
            'is_group' => fake()->boolean(),
            'grand_total' => $grandTotal,
            'pay' => $pay,
            'charge' => $charge,
            'payment_method' => 'cash',
            'payment_ref' => '',
            'gate' => 1,
            'purchase_date' => $purchaseDate,
            'created_at' => $purchaseDate,
            'note' => fake()->title(),
        ];
    }
}
