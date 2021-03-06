<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('name',10);
            $table->string('formal_name',60)->nullable();
            $table->string('transformed_name',10)->nullable();
            $table->string('transformed_formula',10)->nullable();
            $table->integer('no_of_decimal_places',false)->default(2);
            $table->longText('description')->nullable();
            $table->boolean('status')->default(true);
            $table->string('locale',20)->default('en-US')->comments('English, Bangla');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->index('name');
            $table->unique(array('company_id', 'name'));
            $table->index('company_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_units');
    }
}
