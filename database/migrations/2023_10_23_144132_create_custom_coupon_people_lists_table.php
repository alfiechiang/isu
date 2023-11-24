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
        Schema::create('custom_coupon_people_lists', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code')->comment('優惠價卷代碼');
            $table->string('coupon_name')->comment('優惠價卷名稱');
            $table->string('guid')->comment("會員GUID");
            $table->string('phone', 10)->nullable()->comment("手機");
            $table->string('email')->nullable()->comment("Email");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_coupon_people_lists');
    }
};
