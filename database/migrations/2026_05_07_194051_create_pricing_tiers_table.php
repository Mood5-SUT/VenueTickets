<?php
// database/migrations/2024_01_01_000005_create_pricing_tiers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name'); // early_bird, vip, group, regular
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->nullable(); // null = unlimited
            $table->integer('sold_count')->default(0);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->integer('max_per_order')->nullable();
            $table->integer('min_per_order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_tiers');
    }
};