<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->string('phone_number', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('fullname')->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->text('cancel_note')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->string('shipping_type')->default('standard'); // standard | express
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->string('img_refunded_money')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
