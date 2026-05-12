<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->string('row');
            $table->string('seat_number');
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->unique(['event_id', 'row', 'seat_number'], 'unique_seat_hold');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_holds');
    }
};
