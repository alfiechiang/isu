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
        Schema::create('stamp_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('customer_id')->nullable()->comment("顧客ID");
            $table->integer('type')->nullable()->comment("操作類型 EX:新增 刪除");
            $table->timestamp('created_at')->nullable()->comment("生成時間");
            $table->timestamp('expired_at')->nullable()->comment("到期時間");
            $table->string('operator')->nullable()->comment("操作人");
            $table->string('operator_ip')->nullable()->comment("操作者ip");
            $table->string('desc')->nullable()->comment("備註");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stamp_logs');
    }
};
