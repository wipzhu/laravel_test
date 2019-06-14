<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('userId');
            $table->string('password')->comment('密码');
            $table->string('userName', 200)->unique()->comment('用户名');
            $table->string('mobile', 50)->unique()->comment('手机号码');
            $table->string('email', 50)->unique()->comment('邮箱');
            $table->string('api_token', 255)->unique()->comment('API token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
