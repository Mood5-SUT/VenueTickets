<?php
// database/migrations/2024_01_01_000004_create_seats_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->foreignId('seat_map_id')->constrained('seat_maps')->onDelete('cascade');
            $table->string('seat_number');
            $table->string('row_label');
            $table->integer('row_number');
            $table->integer('column_number');
            $table->string('status')->default('available'); // available, locked, reserved, sold
            $table->decimal('price_override', 10, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};