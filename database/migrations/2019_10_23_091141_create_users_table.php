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
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('role_id')->unsigned()->default(3);
            $table->foreign('role_id')->references('id')->on('user_roles')->onDelete('CASCADE');
            $table->bigInteger('emp_id')->unsigned()->nullable();
            $table->string('full_name',220);
            $table->string('name',220)->unique();
            $table->char('short_name',25)->nullable();
            $table->string('email',190)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('b_pass')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('ip_address',15)->nullable(); // last login ip
            $table->string('ip_allow',15)->nullable();// allow login only from this IP
            $table->string('device')->nullable();
            $table->integer('wrong_pass_count',false)->default(0);
            $table->tinyInteger('password_expiry_days')->default(30);
            $table->timestamp('password_updated_at');
            $table->boolean('pass_never_exp')->default(0);
            $table->boolean('status')->default(1);
            $table->unsignedInteger('old_id')->nullable();
            $table->bigInteger('user_created')->default(1);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->unique(['company_id','name']);
            $table->index('name');
            $table->index('role_id');
//            $table->rememberToken();
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
