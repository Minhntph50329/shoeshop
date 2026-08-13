<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        // Seed 10 order statuses
        \DB::table('order_statuses')->insert([
            ['id' => 1,  'name' => 'Chờ xác nhận'],
            ['id' => 2,  'name' => 'Chờ lấy hàng'],
            ['id' => 3,  'name' => 'Đang giao'],
            ['id' => 4,  'name' => 'Giao hàng thành công'],
            ['id' => 5,  'name' => 'Chờ trả hàng'],
            ['id' => 6,  'name' => 'Đã trả hàng'],
            ['id' => 7,  'name' => 'Hoàn tiền'],
            ['id' => 8,  'name' => 'Đã hủy'],
            ['id' => 9,  'name' => 'Gửi hàng'],
            ['id' => 10, 'name' => 'Nhận hàng thành công'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};
