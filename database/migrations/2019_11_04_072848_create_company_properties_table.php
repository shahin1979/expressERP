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
            $table->boolean('cost_center')->default(0);
            $table->boolean('inventory')->default(0);
            $table->boolean('auto_ledger')->default(0);
            $table->boolean('auto_delivery')->default(0);
            $table->integer('cash',false)->default(101);
            $table->integer('bank',false)->default(102);
            $table->integer('sales',false)->default(301);
            $table->integer('purchase',false)->default(201);
            $table->integer('capital',false)->default(501);
            $table->string('default_cash',8)->default('10112102');
            $table->string('default_purchase',8)->default('20112002');
            $table->string('default_sales',8)->default('30112102');
            $table->string('advance_sales',8)->nullable()->default('20312102');
            $table->string('default_sales_tax',8)->default('20212102')->coment('Liability');
            $table->string('default_purchase_tax',8)->default('40212102')->comment('Expenditure during purchase');
            $table->string('discount_purchase',8)->default('30212102');
            $table->string('discount_sales',8)->default('40112110');
            $table->string('consumable_on_hand',8)->default('10412102');
            $table->string('consumable_expense',8)->default('40112102');
            $table->string('rm_in_hand',8)->default('10412104');
            $table->string('work_in_progress',8)->default('10412106');
            $table->string('finished_goods',8)->default('10412110');
            $table->string('depreciation_account',8)->default('40312102');
            $table->char('depreciation_frequency',1)->default('Y')->comment('Y=Yearly, H=Haf Yearly, M=Monthly');
            $table->char('currency',3)->nullable();
            $table->date('fp_start');
            $table->date('trans_min_date');
            $table->string('company_logo',150)->nullable();
            $table->string('company_logo2',150)->nullable();
            $table->boolean('posted')->default(false);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
