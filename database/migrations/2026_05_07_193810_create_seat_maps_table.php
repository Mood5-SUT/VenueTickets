<?php
// database/migrations/2024_01_01_000002_create_seat_maps_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_maps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('venue_id')->nullable()->constrained('venues')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->json('layout_data'); // Grid layout configuration
            $table->integer('total_seats');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_maps');
    }
};