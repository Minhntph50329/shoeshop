<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('bank_account');
            $table->string('user_bank_name');
            $table->string('bank_name');
            $table->text('reason');
            $table->text('aadmin_reason')->nullable();
            $table->string('reason_image')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, completed
            $table->boolean('is_send_money')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
