<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default payment methods
        \DB::table('payments')->insert([
            ['name' => 'Thanh toán khi nhận hàng (COD)', 'logo' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thanh toán VNPay', 'logo' => 'vnpay.png', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
