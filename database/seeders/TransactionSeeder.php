<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::factory(100)->has(TransactionDetail::factory()->count(3), 'details')->create();
    }
}
