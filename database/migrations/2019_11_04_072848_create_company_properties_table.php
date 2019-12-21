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
            $table->date('trans_min_date');
            $table->string('company_logo',150)->nullable();
            $table->string('company_logo2',150)->nullable();
            $table->boolean('posted')->default(false);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        DB::unprepared('
        CREATE OR REPLACE TRIGGER tr_company_properties_updated_at BEFORE INSERT OR UPDATE ON company_properties FOR EACH ROW
            BEGIN
                :NEW.updated_at := SYSDATE;
            END;
        ');
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
