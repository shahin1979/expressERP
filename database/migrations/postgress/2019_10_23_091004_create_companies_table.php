<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('group_companies')->onDelete('CASCADE');
            $table->string('name',240);
            $table->string('address',200);
            $table->string('city',200);
            $table->string('state',200)->nullable();
            $table->string('post_code',200)->nullable();;
            $table->string('country',100);
            $table->string('phone_no',200)->nullable();;
            $table->string('email',190)->unique()->nullable();
            $table->string('website',190)->nullable();
            $table->char('currency',3)->default('BDT');
            $table->string('locale',20)->default('en-US')->comments('English, Bangla');
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
        Schema::dropIfExists('companies');
    }
}
