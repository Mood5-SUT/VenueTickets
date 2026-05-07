<?php
// database/migrations/2024_01_01_000015_create_failed_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('failed_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->string('payment_provider'); // stripe, paypal
            $table->string('error_code')->nullable();
            $table->text('error_message');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_payments');
    }
};