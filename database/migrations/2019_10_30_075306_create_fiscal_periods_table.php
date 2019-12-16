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
            $table->string('fiscal_year',9);
            $table->integer('year',false);
            $table->integer('fp_no',false);
            $table->integer('month_serial',false);
            $table->string('month_name',9);
            $table->date('start_date');
            $table->date('end_date');
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
    }
}
