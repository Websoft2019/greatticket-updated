<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('otp')->nullable();
            $table->datetime('otp_expires_at')->nullable();
            $table->boolean('password_reset_required')->default(false);
            $table->string('password')->nullable();
            $table->enum('role',['a','o','u'])->default('u'); // a => admin, o => organizer, u => user
            $table->enum('gender',['male','female','others'])->default('male');
            $table->foreignId('religion_id')->nullable()->references('id')->on('religions')->onDelete('cascade');
            $table->date('dob')->nullable();
            $table->string('contact')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
