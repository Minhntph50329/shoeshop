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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->text('gallery')->nullable(); // JSON hoặc text danh sách URL ảnh
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->decimal('discount', 15, 2)->nullable();
            $table->dateTime('discount_start')->nullable();
            $table->dateTime('discount_end')->nullable();
            $table->enum('status', ['draft', 'active', 'hidden'])->default('active');
            $table->longText('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
