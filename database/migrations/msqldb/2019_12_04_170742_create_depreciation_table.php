<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepreciationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depreciation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('acc_no',8);
            $table->integer('fp_no',false);
            $table->char('fiscal_year',9);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('open_bal',15,2)->default(0);
            $table->decimal('additional_bal',15,2)->default(0);
            $table->decimal('total_bal',15,2)->default(0);
            $table->decimal('dep_rate',5,2)->default(0);
            $table->decimal('dep_amt',15,2)->default(0);
            $table->decimal('closing_bal',15,2)->default(0);
            $table->boolean('approve_status')->default(0);
            $table->date('approve_date')->nullable();
            $table->string('contra_acc',8);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('authorizer_id')->unsigned()->nullable();
            $table->foreign('authorizer_id')->references('id')->on('users');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->index('fp_no');
            $table->index('start_date');
            $table->index('end_date');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('depreciation');
    }
}
