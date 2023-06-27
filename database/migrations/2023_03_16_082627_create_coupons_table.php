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
            $table->comment("優惠券");

            $table->uuid('id')->primary()->comment("優惠券ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");
            $table->timestamp('updated_at')->nullable()->comment("更新時間");
            $table->softDeletes()->comment("刪除時間");

            $table->string('name')->nullable()->comment("優惠券名稱");
            $table->string('description')->nullable()->comment("優惠券描述");
            $table->string('mode',64)->comment("模式");
            $table->string('type',64)->index()->comment("類型");
            $table->integer('validity')->nullable()->comment("優惠券代碼有效期限，以分鐘為單位");
            $table->timestamp('start_at')->nullable()->comment("優惠券開始時間");
            $table->timestamp('end_at')->nullable()->comment("優惠券結束時間");
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
