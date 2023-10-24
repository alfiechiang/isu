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
        Schema::create('coupon_disable_logs', function (Blueprint $table) {
            $table->id();
            $table->string('guid')->comment("會員GUID");
            $table->string('coupon_code')->comment("優惠卷代碼");
            $table->string('coupon_name')->nullable()->comment("優惠卷名稱");
            $table->string('operator')->nullable()->comment("操作人員");
            $table->timestamp('disable_time')->nullable()->comment("兌換時間");
            $table->string('desc')->nullable()->comment("備註");
            $table->string('operator_ip')->nullable()->comment("操作人員IP");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_disable_logs');
    }
};
