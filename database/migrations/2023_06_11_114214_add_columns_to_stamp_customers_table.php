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
        Schema::table('stamp_customers', function (Blueprint $table) {
            $table->integer('remain_quantity')->default(0)->comment("現有章數");
            $table->integer('value')->default(0)->comment("章數");
            $table->boolean('is_redeem')->default(false)->comment("是否兌換");

            $table->uuid('store_id')->nullable()->comment("商店ID");
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('SET NULL');
            $table->dropColumn(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stamp_customers', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn(['remain_quantity', 'value', 'is_redeem', 'store_id']);
            $table->enum('status', ['unused', 'used'])->default('unused')->nullable()->comment("狀態");
        });
    }
};
