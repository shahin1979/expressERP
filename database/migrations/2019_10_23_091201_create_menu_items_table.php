<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned()->default(1);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('module_id')->unsigned()->default(1);
            $table->foreign('module_id')->references('id')->on('app_modules')->onDelete('CASCADE');
            $table->smallInteger('nav_label');
            $table->string('div_class',200)->nullable();
            $table->string('i_class',200)->nullable();
            $table->char('menu_type',2)->comment('CM=Static Menu MM = Main Menu, SM=Sub Menu');
            $table->char('menu_prefix',2);
//            $table->string('menu_id',3)->unique();
            $table->string('name',100);
            $table->boolean('show')->default(true);
            $table->string('url',300)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
