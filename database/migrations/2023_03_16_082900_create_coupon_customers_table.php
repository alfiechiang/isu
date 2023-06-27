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
        Schema::create('coupon_customers', function (Blueprint $table) {
            $table->comment("顧客領取優惠券紀錄");

            $table->uuid('id')->primary()->comment("紀錄ID");
            $table->timestamp('created_at')->nullable()->comment("領取時間");
            $table->timestamp('expired_at')->nullable()->comment("到期時間");

            $table->enum('status', ['unused', 'used'])->default('unused')->nullable()->comment("狀態");

            $table->string('memo')->nullable()->comment("備註");

            $table->uuid('coupon_id')->nullable()->comment("優惠券ID");
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('SET NULL');

            $table->uuid('coupon_code_id')->nullable()->comment("代碼ID");
            $table->foreign('coupon_code_id')->references('id')->on('coupon_codes')->onDelete('SET NULL');

            $table->uuid('customer_id')->nullable()->comment("顧客ID");
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->string("operator_type")->nullable()->comment("操作人員類型");
            $table->uuid("operator_id")->nullable()->comment("操作人員ID");
            $table->index(["operator_type", "operator_id"], "operator");

            $table->string("reference_type")->nullable()->comment("引用物件類型");
            $table->uuid("reference_id")->nullable()->comment("引用物件ID");
            $table->index(["reference_type", "reference_id"], "reference");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_customers');
    }
};
