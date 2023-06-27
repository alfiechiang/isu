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
        Schema::create('otps', function (Blueprint $table) {
            $table->comment("OTP驗證");

            $table->uuid('id')->primary()->comment("OTP ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");
            $table->timestamp('updated_at')->nullable()->comment("更新時間");

            $table->string('identifier')->index()->comment("綁定到 OTP 的身份");
            $table->string('token')->index()->comment("OTP Token");
            $table->timestamp('expired_at')->nullable()->comment("過期時間");
            $table->boolean('valid')->default(true)->comment("OTP 是否可用");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
