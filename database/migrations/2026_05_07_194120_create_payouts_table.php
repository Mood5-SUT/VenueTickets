<?php
// database/migrations/2024_01_01_000012_create_payouts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->string('stripe_payout_id')->nullable();
            $table->string('stripe_transfer_id')->nullable();
            $table->dateTime('period_start');
            $table->dateTime('period_end');
            $table->text('description')->nullable();
            $table->json('order_ids')->nullable(); // Array of order IDs included
            $table->timestamp('processed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};