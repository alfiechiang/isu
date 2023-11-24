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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment('主標題');
            $table->string('sub_title')->nullable()->comment('副標題');
            $table->integer('type')->comment('聯盟消息:1分館消息:2專案活動:3住宿優惠:4');
            $table->string('web_cover_img')->comment('網頁版');
            $table->string('phone_cover_img')->comment('手機版');
            $table->longText('content')->nullable()->comment('內文');
            $table->string('operator')->nullable()->comment('操作者');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
