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
        Schema::create('guest_users', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('user_agent')->default('unknown');
            $table->string('country')->default('unknown');
            $table->string('city')->default('unknown');
            $table->string('isp')->default('unknown');
            $table->enum('device_os', ['Windows', 'Linux', 'Android', 'iOS', 'unknown'])->default('unknown');
            $table->enum('device_type', ['desktop', 'mobile', 'unknown'])->default('unknown');
            $table->string('device_browser')->default('unknown');
            $table->string('device_language')->default('unknown');
            $table->jsonb('keywords')->default('[]');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_users');
    }
};
