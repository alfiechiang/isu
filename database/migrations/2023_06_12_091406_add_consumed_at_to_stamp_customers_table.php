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
            $table->date('consumed_at')->nullable()->comment("消費日期");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stamp_customers', function (Blueprint $table) {
            $table->dropColumn(['consumed_at']);
        });
    }
};
