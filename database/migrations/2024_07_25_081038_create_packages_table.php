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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title');
            $table->double('cost')->default(0.0);
            $table->double('discount_price')->default(0.0);
            $table->double('actual_cost')->default(0.0);
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedInteger('capacity');
            $table->unsignedInteger('consumed_seat')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
