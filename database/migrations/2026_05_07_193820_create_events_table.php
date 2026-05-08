<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('venue_id')->nullable()->constrained('venues')->onDelete('set null');
            $table->foreignId('seat_map_id')->nullable()->constrained('seat_maps')->onDelete('set null');
            $table->dateTime('event_date');
            $table->dateTime('end_date')->nullable();
            $table->time('doors_open')->nullable();
            $table->string('status')->default('draft');
            $table->string('event_type')->nullable();
            $table->string('image_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->integer('age_restriction')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('resale_enabled')->default(true);
            $table->decimal('resale_price_cap_percentage', 5, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};