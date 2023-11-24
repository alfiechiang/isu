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
        Schema::create('stores', function (Blueprint $table) {
            $table->comment("商店資料");
            $table->string('id')->primary()->comment("商店ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");
            $table->timestamp('updated_at')->nullable()->comment("更新時間");
            $table->softDeletes()->comment("刪除時間");
            $table->string('name', 64)->nullable()->comment("商家名稱");
            $table->string('tax_id')->index()->nullable()->comment("統一編號");
            $table->string('email')->nullable()->comment("Email");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
