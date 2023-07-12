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
            $table->timestamp('consumed_at')->nullable()->comment("兌換日期");
            $table->tinyInteger('status')->default(0)->nullable()->comment("狀態 1可使用 2已使用");
            $table->string('memo')->nullable()->comment("備註");
            $table->uuid('coupon_id')->nullable()->comment("優惠券ID");
            $table->string('coupon_cn_name')->nullable()->comment("中文名稱");
            $table->string('code_script')->nullable()->comment("代碼");
            $table->uuid('customer_id')->nullable()->comment("顧客ID");
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
