<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_status', 50)->default('pending');

            // Midtrans Snap fields
            $table->string('snap_token')->nullable();
            $table->string('snap_url')->nullable();

            // Transaction details
            $table->string('transaction_id')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('payment_type')->nullable();

            // Raw response
            $table->json('raw_response')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};