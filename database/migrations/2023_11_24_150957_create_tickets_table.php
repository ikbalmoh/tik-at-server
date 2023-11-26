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
        Schema::create('tickets', function (Blueprint $table) {
            $table->ulid('id');
            $table->unsignedBigInteger('transaction_detail_id');
            $table->tinyInteger('entrance_max')->default(1);
            $table->tinyInteger('entrance_count')->default(0);
            $table->string('entrance_gate', 10)->nullable()->default('1');
            $table->string('entrance_door', 10)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
