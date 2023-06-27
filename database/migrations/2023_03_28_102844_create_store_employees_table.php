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
        Schema::create('store_employees', function (Blueprint $table) {
            $table->comment("商店員工資料");

            $table->uuid('id')->primary()->comment("商店員工ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");
            $table->timestamp('updated_at')->nullable()->comment("更新時間");
            $table->softDeletes()->comment("刪除時間");

            $table->string('name', 64)->nullable()->comment("姓名");
            $table->string('email')->nullable()->comment("Email");
            $table->string('username')->unique()->comment("帳號");
            $table->string('password')->comment("密碼");

            $table->uuid('store_id')->nullable()->comment("商店ID");
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_employees');
    }
};
