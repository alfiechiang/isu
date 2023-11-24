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
        Schema::create('custom_coupons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->comment('優惠價卷名稱');
            $table->string('code')->unique()->comment('優惠價卷代碼');
            $table->string('img')->comment('圖示');
            $table->integer('per_people_volume')->comment('每人可獲得數量');
            $table->integer('total_issue')->comment('發行總數量');
            $table->timestamp('issue_time')->comment('優惠卷發放時間');
            $table->timestamp('expire_time')->comment('優惠卷到期時間');
            $table->text('coupon_desc')->comment('優戶卷內容說明');
            $table->text('notice_desc')->comment('注意事項內容說明');
            $table->tinyInteger('notify')->comment('優惠卷發送通知 0:無 1:手機號碼 2:信箱');
            $table->tinyInteger('shelve')->default(0)->comment('是否已上架 0:無 1:有上架');
            $table->tinyInteger('disable')->default(0)->comment("失效 0:未失效 1:失效");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_coupons');
    }
};
