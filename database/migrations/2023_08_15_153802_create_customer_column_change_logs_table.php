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
        Schema::create('customer_column_change_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('customer_id')->nullable()->comment("顧客ID");
            $table->string('table_name')->nullable()->comment("table名稱");
            $table->string('column_name')->nullable()->comment("table名稱");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_column_change_logs');
    }
};
