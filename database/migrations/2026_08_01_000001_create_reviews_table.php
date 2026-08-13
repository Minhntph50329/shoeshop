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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('order_item_id')->nullable()->constrained('order_items')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating')->nullable(); // rating from 1 to 5 stars, nullable for admin replies
            $table->unsignedBigInteger('review_id')->nullable(); // parent review ID for replies
            $table->text('review_text');
            $table->string('reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('has_replies')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
        });

        Schema::create('review_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('reviews')->onDelete('cascade');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('reviews');
    }
};
