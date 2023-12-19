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
        Schema::create('access_token_logs', function (Blueprint $table) {
            $table->id();
            $table->text('access_token')->comment("登入token");
            $table->string('uid', 64)->nullable()->comment("後台帳號流水號");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_token_logs');
    }
};
