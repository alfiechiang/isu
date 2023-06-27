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
        Schema::create('invoices', function (Blueprint $table) {
            $table->comment("發票");

            $table->uuid('id')->primary()->comment("發票ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");

            $table->string('image')->nullable()->comment("發票圖片");
            $table->string('number')->index()->comment("發票號碼");
            $table->integer('amount')->comment("總金額");
            $table->date('purchased_at')->comment("發票日期");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
