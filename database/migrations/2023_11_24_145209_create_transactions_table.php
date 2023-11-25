<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('operator_id');
            $table->smallInteger('qty')->default(1);
            $table->boolean('is_group')->default(false);
            $table->double('price');
            $table->double('subtotal');
            $table->double('discount')->default(0);
            $table->double('total');
            $table->double('pay');
            $table->double('charge')->default(0);
            $table->string('payment_method')->default('cash');
            $table->string('gate', 10)->default('1');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('ticket_types');
            $table->foreign('operator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
