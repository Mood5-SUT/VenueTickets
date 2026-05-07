<?php
// database/migrations/2024_01_01_000009_create_promo_codes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type')->default('percentage'); // percentage, fixed_amount
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->integer('max_uses_per_user')->default(1);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at');
            $table->boolean('is_active')->default(true);
            $table->string('scope')->default('global'); // global, event_specific
            $table->text('description')->nullable();
            $table->json('applicable_events')->nullable(); // Array of event IDs if scope is event_specific
            $table->json('applicable_tiers')->nullable(); // Array of pricing tier IDs
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};