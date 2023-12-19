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
        Schema::table('custom_coupons', function (Blueprint $table) {
            $table->tinyInteger('send')->default(0)->after('notify')->comment('是否派送');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_coupons', function (Blueprint $table) {
            //
        });
    }
};
