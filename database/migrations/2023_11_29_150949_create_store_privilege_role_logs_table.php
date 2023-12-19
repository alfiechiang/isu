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
        Schema::create('store_privilege_role_logs', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 64)->nullable()->comment("後台帳號流水號");
            $table->text('access_token')->comment("登入token");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_privilege_role_logs');
    }
};
