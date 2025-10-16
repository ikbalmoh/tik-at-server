<?php

namespace Database\Factories;

use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionDetail>
 */
class TransactionDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 50000, 20000);
        $qty = fake()->randomDigit();
        $discount = fake()->randomFloat(2, 50000, 20000);
        $type = TicketType::first();

        return [
            'ticket_type_id' => $type->id,
            'price' => $price,
            'qty' => $qty,
            'subtotal' => $price * $qty,
            'discount' => $discount,
            'total' => ($price * $qty) - $discount,
        ];
    }
}
