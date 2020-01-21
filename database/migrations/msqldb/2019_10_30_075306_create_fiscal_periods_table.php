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
            $table->char('status',1)->default('A'); // A=Active  C=Closed
            $table->boolean('depreciation');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('fp_no');
            $table->index('status');
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
        Schema::dropIfExists('fiscal_periods');
        DB::unprepared('DROP TRIGGER tr_fiscal_periods_updated_at');
    }
}
