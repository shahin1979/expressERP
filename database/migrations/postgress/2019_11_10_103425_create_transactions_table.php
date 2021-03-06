<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('period',8)->nullable();
            $table->string('tr_code',3)->nullable();
            $table->smallInteger('trans_type_id')->unsigned()->default(6);
            $table->foreign('trans_type_id')->references('id')->on('trans_types')->onDelete('CASCADE');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE');
            $table->integer('fp_no',false)->default(0);
            $table->string('ref_no',12)->nullable();
            $table->string('cheque_no',20)->nullable();
            $table->string('cost_center',4)->nullable();
            $table->string('trans_id',18);
            $table->string('trans_group_id',18);
            $table->date('trans_date');
            $table->bigInteger('voucher_no',false);
            $table->string('acc_no',8);
            $table->decimal('dr_amt',15,2)->default(0);
            $table->decimal('cr_amt',15,2)->default(0);
            $table->decimal('trans_amt',15,2)->default(0);
            $table->string('contra_acc',8)->nullable();
            $table->string('currency',3)->nullable();
            $table->decimal('fc_amt',15,2)->default(0);
            $table->decimal('exchange_rate',8,2)->default(1);
            $table->string('fiscal_year',9)->nullable();
            $table->string('trans_desc1',240)->nullable();
            $table->string('trans_desc2',240)->nullable();
            $table->string('remote_desc',240)->nullable();
            $table->boolean('post_flag')->default(0);
            $table->date('post_date')->nullable();
            $table->bigInteger('authorizer_id')->unsigned()->nullable();
            $table->foreign('authorizer_id')->references('id')->on('users');
            $table->boolean('jv_flag')->nullable();
            $table->boolean('export_flag')->nullable();
            $table->boolean('tr_state')->default(0)->comment('0=>valid 1=>reversed');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->index('acc_no');
            $table->index('trans_date');
            $table->index('voucher_no');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
//        DB::unprepared('DROP TRIGGER TR_TRANSACTIONS_UPDATED_AT');
//        DB::unprepared('DROP SEQUENCE TRANSACTIONS_ID_SEQ');
    }
}
