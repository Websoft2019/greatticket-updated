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
        Schema::create('ticket_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_package_id');
            $table->string('name');
            $table->string('ic')->nullable();
            $table->string('membership_no')->unique()->nullable();
            $table->enum('ticket_type', ['paid', 'complementary'])->default('paid');
            $table->enum('gender',['male','female','others'])->default('male');
            $table->string('qr_code')->unique()->nullable();
            $table->string('qr_image')->nullable();
            $table->dateTime('checkedin')->nullable();
            $table->foreign('order_package_id')->references('id')->on('order_package')->onDelete('cascade');
            $table->foreignId('seat_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_users');
    }
};
