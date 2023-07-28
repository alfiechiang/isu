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
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->string('store_uid', 64)->nullable()->comment("店家流水號");
            $table->integer('exchange_num')->nullable()->comment("一次可兌換的數量");
            $table->integer('spend_stamp_num')->nullable()->comment("一次可兌換的數量");
            $table->integer('stock')->nullable()->comment("一次可兌換的數量");
            $table->string('item_name')->nullable()->comment("品項名稱");
            $table->string('desc')->nullable()->comment("備註");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
