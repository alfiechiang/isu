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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name')->nullable()->comment('店家名稱');
            $table->string('phone')->nullable()->comment('電話');
            $table->string('hotel_url')->nullable()->comment('店家網址');
            $table->longText('hotel_desc')->nullable()->comment('店家簡述');
            $table->string('district')->nullable()->nullable()->comment("區");
            $table->string('country')->nullable()->comment('國家');
            $table->string('county')->nullable()->comment('縣市');
            $table->string('google_map_url')->nullable()->comment('縣市');
            $table->string('address')->nullable()->comment('地址');
            $table->string('desc')->nullable()->comment('備註');
            $table->string('slogan')->nullable()->comment('標語');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
