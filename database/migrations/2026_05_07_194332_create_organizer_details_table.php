<?php
// database/migrations/2024_01_01_000011_create_organizer_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name');
            $table->string('tax_id')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, approved, suspended, rejected
            $table->text('rejection_reason')->nullable();
            $table->text('suspension_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('stripe_connect_id')->nullable();
            $table->boolean('payouts_enabled')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizer_details');
    }
};