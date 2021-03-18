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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('')->comment('姓名');
            $table->string('email')->index();
            $table->string('password');
            $table->string('verifyToken')->nullable()->comment('驗證token');
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('isToken')->default(0)->comment('是否驗證token');
            $table->tinyInteger('isOauth')->default(0)->comment('是否有使用oauth');
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
