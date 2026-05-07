<?php
// database/migrations/2024_01_01_000003_create_zones_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_map_id')->constrained('seat_maps')->onDelete('cascade');
            $table->string('name');
            $table->string('color')->default('#007bff');
            $table->decimal('default_price', 10, 2);
            $table->integer('capacity');
            $table->integer('rows')->default(1);
            $table->integer('columns')->default(1);
            $table->json('seat_numbers')->nullable(); // Custom seat numbering
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};