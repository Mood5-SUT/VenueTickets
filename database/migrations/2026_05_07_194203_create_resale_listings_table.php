<?php
// database/migrations/2024_01_01_000008_create_resale_listings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resale_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->decimal('original_price', 10, 2);
            $table->decimal('asking_price', 10, 2);
            $table->string('status')->default('active'); // active, sold, cancelled, flagged, removed
            $table->boolean('is_flagged')->default(false);
            $table->text('flag_reason')->nullable();
            $table->decimal('price_cap_percentage', 5, 2)->nullable();
            $table->boolean('exceeds_price_cap')->default(false);
            $table->timestamp('sold_at')->nullable();
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resale_listings');
    }
};