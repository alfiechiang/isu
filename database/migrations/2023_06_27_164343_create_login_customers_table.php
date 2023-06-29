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
        Schema::create('login_customers', function (Blueprint $table) {
            $table->comment("顧客登入");

            $table->uuid('id')->primary()->comment("登入ID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");

            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->uuid('customer_id')->nullable()->comment("顧客ID");
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_customers');
    }
};
