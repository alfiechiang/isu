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
        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->comment("優惠券代碼");

            $table->uuid('id')->primary()->comment("代碼ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");
            $table->timestamp('updated_at')->nullable()->comment("更新時間");

            $table->string('code')->unique()->nullable()->comment("優惠券代碼");

            $table->uuid('coupon_id')->nullable()->comment("優惠券ID");
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_codes');
    }
};
