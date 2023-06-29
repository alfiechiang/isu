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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->comment("社群帳號");

            $table->uuid('id')->primary()->comment("社群帳號ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");
            $table->timestamp('updated_at')->nullable()->comment("更新時間");
            $table->softDeletes()->comment("刪除時間");

            $table->uuid('customer_id')->nullable()->comment("會員ID");
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->string('provider_name')->nullable()->comment("社群來源");
            $table->string('provider_id')->unique()->nullable()->comment("社群上的用戶ID");

            $table->text('access_token')->nullable();

            $table->index(['provider_name', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
