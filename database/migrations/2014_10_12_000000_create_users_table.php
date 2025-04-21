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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->enum('gender', ['m', 'f']);
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->string('city')->nullable();
            $table->string('image')->default('default.jpg')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->integer('age')->nullable();
            $table->integer('code')->nullable();
            $table->timestamp('code_expired_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
