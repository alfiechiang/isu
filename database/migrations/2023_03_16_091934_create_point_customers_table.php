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
            $table->tinyInteger('type')->comment("類型 1:兌換集章2:進店掃描3:消費認證4:系統新增");
            $table->string("operator")->nullable()->comment("操作人員");
            $table->string('operator_ip')->nullable()->comment("操作人員ip");
            $table->index( "operator");
            $table->string('desc')->nullable()->comment("備註");
            $table->integer('value')->default(0)->comment("點數");

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
