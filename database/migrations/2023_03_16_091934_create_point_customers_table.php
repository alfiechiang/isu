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
        Schema::create('point_customers', function (Blueprint $table) {
            $table->comment("顧客點數");

            $table->uuid('id')->primary()->comment("點數ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");
            $table->timestamp('expired_at')->nullable()->comment("點數到期時間");

            $table->string('source')->index()->nullable()->comment("點數來源");
            $table->tinyInteger('type')->comment("類型");

            $table->string("operator_type")->nullable()->comment("操作人員類型");
            $table->uuid("operator_id")->nullable()->comment("操作人員ID");
            $table->index(["operator_type", "operator_id"], "operator");

            $table->string("reference_type")->nullable()->comment("引用物件類型");
            $table->uuid("reference_id")->nullable()->comment("引用物件ID");
            $table->index(["reference_type", "reference_id"], "reference");

            $table->string('memo')->nullable()->comment("點數更動原因");

            $table->integer('point_balance')->default(0)->comment("現有點數");
            $table->integer('value')->default(0)->comment("點數");
            $table->boolean('is_redeem')->default(false)->comment("是否兌換");

            $table->uuid('customer_id')->nullable()->comment("顧客ID");
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_customers');
    }
};
