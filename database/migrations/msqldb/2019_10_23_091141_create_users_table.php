<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('RESTRICT');
            $table->bigInteger('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('user_roles')->onDelete('RESTRICT');
            $table->integer('emp_id')->unsigned()->nullable();
            $table->string('login_name',220);
            $table->string('name',220);
            $table->char('short_name',25)->nullable();
            $table->string('email',190)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('b_pass')->nullable();
            $table->timestamp('lastlogin')->nullable();
            $table->ipAddress('visitor')->nullable();
            $table->macAddress('device')->nullable();
            $table->integer('wrongpasscount',false)->default(0);
            $table->date('pass_exp_date')->nullable();
            $table->integer('pass_exp_period')->unsigned()->default(3);
            $table->boolean('pass_never_exp')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->rememberToken();
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
    }
}
