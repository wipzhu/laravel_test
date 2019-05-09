<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager', function (Blueprint $table) {
            $table->increments('mg_id')->comment('主键');
            $table->string('username', 64)->comment('名称');
            $table->char('password', 60)->comment('mima ');
            $table->string('mg_role_ids')->nullable()->comment('角色ids');
            $table->enum('mg_sex', ['男', '女'])->nullable()->comment('性别');
            $table->char('mg_phone', 11)->nullable()->comment('手机号码');
            $table->string('mg_email')->nullable()->comment('邮箱');
            $table->text('mg_remark')->nullable()->comment('邮箱');
            $table->timestamps(); // 生成created_at和update_at
            $table->softDeletes(); // 软删除 delete_at
            $table->rememberToken();
            $table->unique('username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager');
    }
}
