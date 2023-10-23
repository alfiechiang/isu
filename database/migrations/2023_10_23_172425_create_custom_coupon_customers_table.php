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
        Schema::create('custom_coupon_customers', function (Blueprint $table) {
            $table->id();
            $table->string('guid')->comment("會員GUID");
            $table->string('coupon_code')->comment("優惠卷代碼");
            $table->string('coupon_name')->nullable()->comment("優惠卷名稱");
            $table->string('customer_name')->nullable()->comment("會員姓名");
            $table->string('phone')->nullable()->comment("手機");
            $table->string('email')->nullable()->comment("信箱");
            $table->timestamp('exchange_time')->nullable()->comment("兌換時間");
            $table->string('exchange_place')->nullable()->comment("兌換時間");
            $table->string('exchanger')->nullable()->comment("兌換人員");
            $table->timestamp('expire_time')->nullable()->comment('優惠卷到期時間');
            $table->tinyInteger('exchange')->default(0)->comment("兌換:1 未兌換:0");
            $table->tinyInteger('disable')->default(0)->comment("失效 0:未失效 1:失效");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_coupon_customers');
    }
};
