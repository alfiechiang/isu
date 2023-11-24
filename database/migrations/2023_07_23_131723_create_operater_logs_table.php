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
        Schema::create('operater_logs', function (Blueprint $table) {
            $table->id();
            $table->string('operator_email')->comment("操作者信箱");
            $table->string('email')->nullable()->comment("信箱");
            $table->string('guid')->nullable()->comment("會員GUID");
            $table->string("area")->comment("模塊區域");
            $table->string("column")->comment("欄位");
            $table->string("type")->comment("新增 create 刪除delete 更新update");
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operater_logs');
    }
};
