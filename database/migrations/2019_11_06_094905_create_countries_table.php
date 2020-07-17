<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->char('short_code',2)->unique();
            $table->char('country_code',3)->unique();
            $table->string('country_name',60)->unique();
            $table->string('nick_name',60)->nullable();
            $table->char('currency_code',4)->nullable();
            $table->char('currency_short',3)->nullable();
            $table->string('currency',60)->nullable();
            $table->string('currency_symbol',4)->nullable();
            $table->string('phone_code',10)->nullable();
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
