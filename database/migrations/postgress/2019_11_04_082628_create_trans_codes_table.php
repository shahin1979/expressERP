<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('trans_code',2);
            $table->string('trans_name',20);
            $table->bigInteger('last_trans_id',false);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->index('trans_code');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_codes');
    }
}
