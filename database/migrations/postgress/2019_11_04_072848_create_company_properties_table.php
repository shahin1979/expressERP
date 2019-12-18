<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned()->unique();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->boolean('project')->default(0);
            $table->boolean('inventory')->default(0);
            $table->boolean('auto_ledger')->default(0);
            $table->integer('cash',false)->default(101);
            $table->integer('bank',false)->default(102);
            $table->integer('sales',false)->default(301);
            $table->integer('purchase',false)->default(401);
            $table->integer('capital',false)->default(501);
            $table->char('currency',3)->nullable();
            $table->date('fp_start');
            $table->boolean('posted')->default(false);
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
        Schema::dropIfExists('company_properties');
//        DB::unprepared('DROP TRIGGER tr_company_properties_updated_at');
    }
}