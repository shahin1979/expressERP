<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBangladeshesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangladesh', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('lang',2);
            $table->string('division',20);
            $table->string('district',25);
            $table->string('thana',60);
            $table->string('upazila',60)->nullable();
            $table->string('post_office',60);
            $table->string('post_code',4);
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
        Schema::dropIfExists('bangladeshes');
        DB::unprepared('DROP SEQUENCE BANGLADESH_ID_SEQ');
    }
}
