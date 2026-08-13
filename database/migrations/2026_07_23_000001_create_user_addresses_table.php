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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('address');
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('street')->nullable();
            $table->string('latidute')->nullable();
            $table->string('longtidute')->nullable();
            $table->string('address_type')->default('home');
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fullname')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
