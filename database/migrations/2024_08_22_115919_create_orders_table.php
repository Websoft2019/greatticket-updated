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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->decimal('carttotalamount');
            $table->decimal('servicecharge');
            $table->double('discount_amount')->default(0.0);
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->decimal('grandtotal');
            $table->string('paymentmethod')->nullable();
            $table->enum('paymentstatus', ['Y', 'N', 'R'])->default('N');
            $table->string('payerid')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('qr_image')->nullable();
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
