<?php
// database/migrations/2024_01_01_000007_create_tickets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('qr_code')->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pricing_tier_id')->nullable()->constrained('pricing_tiers')->onDelete('set null');
            $table->foreignId('seat_id')->nullable()->constrained('seats')->onDelete('set null');
            $table->string('section')->nullable();
            $table->string('row')->nullable();
            $table->string('seat_number')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('status')->default('active'); // active, used, voided, transferred
            $table->dateTime('checked_in_at')->nullable();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('transfer_code')->nullable()->unique();
            $table->foreignId('transferred_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('transferred_at')->nullable();
            $table->text('void_reason')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};