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
        Schema::create('stamp_customer_redeems', function (Blueprint $table) {
            $table->comment("顧客兌換集章紀錄");
            $table->uuid('id')->primary()->comment("紀錄ID");
            $table->timestamp('created_at')->nullable()->comment("兌換時間");
            $table->string("operator_type")->nullable()->comment("操作人員類型");
            $table->uuid("operator_id")->nullable()->comment("操作人員ID");
            $table->index(["operator_type", "operator_id"], "operator");
            $table->string("reference_type")->nullable()->comment("引用物件類型");
            $table->uuid("reference_id")->nullable()->comment("引用物件ID");
            $table->index(["reference_type", "reference_id"], "reference");
            $table->string('memo')->nullable()->comment("備註");
            $table->integer('stamp_customer_id')->nullable()->comment("顧客集章ID");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stamp_customer_redeems');
    }
};
