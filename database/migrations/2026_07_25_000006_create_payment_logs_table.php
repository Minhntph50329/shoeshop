<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('txn_ref')->nullable(); // VNPay transaction reference
            $table->string('response_code', 10)->nullable();
            $table->string('transaction_no')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('bank_code', 20)->nullable();
            $table->text('response_data')->nullable(); // full JSON response
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
