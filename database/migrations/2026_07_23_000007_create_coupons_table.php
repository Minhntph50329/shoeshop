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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('discount_type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_notified')->default(false);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
