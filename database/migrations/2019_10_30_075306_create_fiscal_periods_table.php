<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiscalPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('FiscalYear',9);
            $table->integer('year',false);
            $table->integer('fpNo',false);
            $table->integer('monthSl',false);
            $table->string('monthName',9);
            $table->date('startDate');
            $table->date('endDate');
            $table->boolean('status');
            $table->boolean('depreciation');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        DB::unprepared('
        CREATE OR REPLACE TRIGGER tr_fiscal_periods_updated_at BEFORE INSERT OR UPDATE ON fiscal_periods FOR EACH ROW
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
        Schema::dropIfExists('fiscal_periods');
        DB::unprepared('DROP TRIGGER tr_fiscal_periods_updated_at');
    }
}
