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
        Schema::create('store_privilege_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable()->comment("名稱");
            $table->string('cn_name', 64)->nullable()->comment("中文名稱");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_privilege_menus');
    }
};
