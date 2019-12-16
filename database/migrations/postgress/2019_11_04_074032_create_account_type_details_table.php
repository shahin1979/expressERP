<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTypeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_type_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_type_id')->unsigned();
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('CASCADE');
            $table->smallInteger('type_code')->unsigned()->unique();
            $table->string('description',100);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_type_details');
    }
}
