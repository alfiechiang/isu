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
        Schema::create('customers', function (Blueprint $table) {
            $table->comment("顧客資料");
            $table->uuid('id')->primary()->comment("顧客ID");
            $table->string('guid')->uinque()->comment("顧客GUID");
            $table->timestamp('created_at')->nullable()->comment("建立時間");
            $table->timestamp('updated_at')->nullable()->comment("更新時間");
            $table->softDeletes()->comment("刪除時間");
            $table->string('name', 64)->nullable()->comment("姓名");
            $table->string('email')->unique()->nullable()->comment("Email");
            $table->string('phone', 10)->unique()->nullable()->comment("手機");
            $table->string('country_code', 5)->nullable()->comment("國碼");
            $table->string('password')->comment("密碼");
            $table->enum('citizenship', ['foreign', 'native'])->nullable()->comment("國籍");
            $table->string('avatar')->nullable()->comment("頭像");
            $table->boolean('join_group')->nullable()->comment("揪團");
            $table->enum('status', ['disabled', 'enabled', 'unverified'])->nullable()->comment("狀態");
            $table->string('gender')->nullable()->comment("性別");
            $table->date('birthday')->index()->nullable()->comment("生日");
            $table->string('desc')->nullable()->comment("備註");
            $table->string('country')->nullable()->comment("國家");
            $table->string('county')->nullable()->comment("縣市");
            $table->string('district')->nullable()->comment("區");
            $table->string('postal')->nullable()->comment("郵遞區號");
            $table->string('address')->nullable()->comment("地址");
            $table->text('interest')->nullable()->comment("興趣");
            $table->integer('point_balance')->default(0)->comment("現有點數");
            $table->integer('stamps')->default(0)->comment("現有集章");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
