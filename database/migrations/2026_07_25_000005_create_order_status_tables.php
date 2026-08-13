<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_order_status', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_status_id')->constrained('order_statuses')->cascadeOnDelete();
            $table->unsignedBigInteger('modified_by')->nullable(); // user id
            $table->text('note')->nullable();
            $table->string('employee_evidence')->nullable(); // file path
            $table->text('customer_confirmation')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();

            $table->primary(['order_id', 'order_status_id']);
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_status_id')->constrained('order_statuses')->cascadeOnDelete();
            $table->unsignedBigInteger('modifier_id')->nullable(); // user id who changed it
            $table->text('note')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_order_status');
    }
};
