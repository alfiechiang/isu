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

            $table->string("operator")->nullable()->after('disable')->comment("操作者");
            $table->string("operator_ip")->nullable()->after('operator')->comment("操作者IP");
            //
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
