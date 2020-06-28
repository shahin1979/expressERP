<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralLedgerBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_ledger_backups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fiscal_year',9);
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('cost_center_id')->unsigned()->nullable();
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('CASCADE');
            $table->string('ledger_code',3)->nullable();
            $table->string('acc_no',8);
            $table->string('acc_name',50);
            $table->string('acc_type',1);
            $table->smallInteger('type_code')->unsigned();
            $table->foreign('type_code')->references('type_code')->on('account_type_details');
            $table->string('acc_range',8)->nullable();
            $table->boolean('is_group')->nullable();
            $table->decimal('opn_dr',15,4)->nullable()->default(0);
            $table->decimal('opn_cr',15,4)->nullable()->default(0);
            $table->decimal('start_dr',15,4)->nullable()->default(0);
            $table->decimal('start_cr',15,4)->nullable()->default(0);
            $table->decimal('curr_bal',15,4)->default(0);
            $table->decimal('cyr_dr',15,4)->nullable()->default(0);
            $table->decimal('cyr_cr',15,4)->nullable()->default(0);
            $table->decimal('dr_00',15,4)->nullable()->default(0);// current year debit with unposted
            $table->decimal('cr_00',15,4)->nullable()->default(0);//current year credit with unposted
            $table->decimal('pyr_dr',15,4)->nullable()->default(0);
            $table->decimal('pyr_cr',15,4)->nullable()->default(0);
            $table->decimal('dr_01',15,4)->nullable()->default(0);
            $table->decimal('cr_01',15,4)->nullable()->default(0);
            $table->decimal('dr_02',15,4)->nullable()->default(0);
            $table->decimal('cr_02',15,4)->nullable()->default(0);
            $table->decimal('dr_03',15,4)->nullable()->default(0);
            $table->decimal('cr_03',15,4)->nullable()->default(0);
            $table->decimal('dr_04',15,4)->nullable()->default(0);
            $table->decimal('cr_04',15,4)->nullable()->default(0);
            $table->decimal('dr_05',15,4)->nullable()->default(0);
            $table->decimal('cr_05',15,4)->nullable()->default(0);
            $table->decimal('dr_06',15,4)->nullable()->default(0);
            $table->decimal('cr_06',15,4)->nullable()->default(0);
            $table->decimal('dr_07',15,4)->nullable()->default(0);
            $table->decimal('cr_07',15,4)->nullable()->default(0);
            $table->decimal('dr_08',15,4)->nullable()->default(0);
            $table->decimal('cr_08',15,4)->nullable()->default(0);
            $table->decimal('dr_09',15,4)->nullable()->default(0);
            $table->decimal('cr_09',15,4)->nullable()->default(0);
            $table->decimal('dr_10',15,4)->nullable()->default(0);
            $table->decimal('cr_10',15,4)->nullable()->default(0);
            $table->decimal('dr_11',15,4)->nullable()->default(0);
            $table->decimal('cr_11',15,4)->nullable()->default(0);
            $table->decimal('dr_12',15,4)->nullable()->default(0);
            $table->decimal('cr_12',15,4)->nullable()->default(0);
            $table->decimal('cyr_bgt_pr',15,4)->nullable()->default(0);
            $table->decimal('cyr_bgt_tr',15,4)->nullable()->default(0);
            $table->decimal('cyr_bgt_ta',15,4)->nullable()->default(0);
            $table->decimal('lyr_bgt_pr',15,4)->nullable()->default(0);
            $table->decimal('lyr_bgt_tr',15,4)->nullable()->default(0);
            $table->decimal('bgt_01',15,4)->nullable()->default(0);
            $table->decimal('bgt_02',15,4)->nullable()->default(0);
            $table->decimal('bgt_03',15,4)->nullable()->default(0);
            $table->decimal('bgt_04',15,4)->nullable()->default(0);
            $table->decimal('bgt_05',15,4)->nullable()->default(0);
            $table->decimal('bgt_06',15,4)->nullable()->default(0);
            $table->decimal('bgt_07',15,4)->nullable()->default(0);
            $table->decimal('bgt_08',15,4)->nullable()->default(0);
            $table->decimal('bgt_09',15,4)->nullable()->default(0);
            $table->decimal('bgt_10',15,4)->nullable()->default(0);
            $table->decimal('bgt_11',15,4)->nullable()->default(0);
            $table->decimal('bgt_12',15,4)->nullable()->default(0);
            $table->decimal('fc_bgt',15,4)->nullable()->default(0);
            $table->decimal('fc_bal_dr',15,4)->nullable()->default(0);
            $table->decimal('fc_bal_cr',15,4)->nullable()->default(0);
            $table->char('currency',3)->nullable()->default('BDT');
            $table->boolean('opn_post')->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->unique(array('fiscal_year','company_id', 'acc_no'));
//            $table->unique(array('fiscal_year','company_id', 'acc_name'));
            $table->index('company_id');
            $table->index('acc_no');
            $table->index('acc_name');
            $table->index('ledger_code');
            $table->index('curr_bal');
            $table->index('fiscal_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_ledger_backups');
    }
}
