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
        Schema::table('custom_coupon_customers', function (Blueprint $table) {
            $table->integer('notify')->default(0)->after('disable')->comment("發送通知");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_coupon_customers', function (Blueprint $table) {
            //
        });
    }
};
