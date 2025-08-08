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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('slug');
            $table->string('title');
            $table->date('date');
            $table->time('time');
            $table->string('vennue');
            $table->string('primary_photo')->nullable();
            $table->string('seat_view')->nullable();
            $table->text('highlight')->nullable();
            $table->string('longitude');
            $table->string('latitude');
            $table->longText('description')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('organizer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
