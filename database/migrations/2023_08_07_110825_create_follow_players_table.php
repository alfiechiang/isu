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
        Schema::create('follow_players', function (Blueprint $table) {
            $table->id();
            $table->string('store_uid')->nullable()->comment('店家UID');
            $table->string('title')->nullable()->comment('主標題');
            $table->string('sub_title')->nullable()->comment('副標題');
            $table->string('copyright')->nullable()->comment('版權宣告');
            $table->string('artist')->nullable()->comment('作者');
            $table->string('link_url')->nullable()->comment('部落客的網址');
            $table->string('area')->nullable()->comment('區域');
            $table->longText('content')->nullable()->comment('內文');
            $table->tinyInteger('review')->default(1)->comment('審核');
            $table->string('operator')->nullable()->comment('操作者');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_players');
    }
};
