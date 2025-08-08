<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('package_id')->constrained()->onDelete('cascade');

            $table->string('row_label');
            $table->string('seat_number');

            $table->unsignedBigInteger('position_x')->nullable();
            $table->unsignedBigInteger('position_y')->nullable();

            $table->enum('status', ['available', 'reserved', 'booked'])->default('available');
            $table->timestamps();

            $table->unique(['package_id', 'row_label', 'seat_number'], 'unique_seat_per_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
