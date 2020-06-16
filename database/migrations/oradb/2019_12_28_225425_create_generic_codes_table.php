<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenericCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generic_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table_name',150);
            $table->string('field_name',50);
            $table->char('generic_code',5);
            $table->char('description',105);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generic_codes');
    }
}
