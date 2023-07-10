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
            $table->string('uid', 64)->nullable()->comment("流水號");
            $table->integer('role_id')->comment("角色ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");
            $table->timestamp('updated_at')->nullable()->comment("更新時間");
            $table->softDeletes()->comment("刪除時間");
            $table->string('name', 64)->nullable()->comment("姓名");
            $table->string('phone', 10)->unique()->nullable()->comment("手機");
            $table->string('email')->unique()->nullable()->comment("Email");
            $table->string('password')->comment("密碼");
            $table->string('desc', 64)->nullable()->comment("備註");
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
