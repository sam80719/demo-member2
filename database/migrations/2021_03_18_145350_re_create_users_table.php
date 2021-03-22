<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReCreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('')->comment('姓名');
            $table->string('email')->index()->comment('信箱');
            $table->string('password')->comment('密碼');
            $table->text('verify_token')->nullable()->comment('驗證token');
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('is_token')->default(0)->comment('是否驗證token');
            $table->tinyInteger('is_oauth')->default(0)->comment('是否有使用oauth');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
//        Schema::table('users', function (Blueprint $table) {
//            //
//        });
    }
}
