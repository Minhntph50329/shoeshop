<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('old_price', 12, 2)->nullable();
            $table->decimal('old_price_variant', 12, 2)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('name_variant')->nullable();
            $table->string('attributes_variant')->nullable(); // JSON string or text
            $table->decimal('price_variant', 12, 2)->nullable();
            $table->unsignedInteger('quantity_variant')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
