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
        Schema::create('recommends', function (Blueprint $table) {
            $table->id();
            $table->string("store_uid")->nullable()->comment("店家UID");
            $table->integer('type')->nullable()->comment("類型");
            $table->string('open_start_time')->nullable()->comment("開始營業時間");
            $table->string('open_end_time')->nullable()->comment("結束營業時間");
            $table->string("name")->nullable()->comment("名稱");
            $table->string("precision")->nullable()->comment("精度");
            $table->string("latitude")->nullable()->comment("緯度");
            $table->string("phone")->nullable()->comment("聯絡電話");
            $table->string("address")->nullable()->comment("地址");
            $table->string("official_website")->nullable()->comment("官網");
            $table->longText("content")->nullable()->comment("敘述");
            $table->string("desc")->nullable()->comment("備註");
            $table->string("image")->nullable()->comment("圖片");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommends');
    }
};
