<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_contracts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('invoice_no',false)->unsigned();
            $table->bigInteger('customer_id',false)->unsigned()->comment('ID from the relationship table');
            $table->foreign('customer_id')->references('id')->on('relationships')->onDelete('CASCADE');
            $table->string('export_contract_no',120);
            $table->date('contract_date');
            $table->date('signing_date');
            $table->date('expiry_date');
            $table->string('loading_port',220)->nullable();
            $table->string('dest_port',220)->nullable();
            $table->unsignedInteger('shipment_time')->default(0);
            $table->unsignedInteger('tolerance_limit')->default(0);
//            $table->bigInteger('importer_bank_id')->unsigned();
//            $table->foreign('importer_bank_id')->references('id')->on('banks')->onDelete('CASCADE');
//            $table->bigInteger('exporter_bank_id')->unsigned();
//            $table->foreign('exporter_bank_id')->references('id')->on('banks')->onDelete('CASCADE');
            $table->string('currency',3)->default('USD');
            $table->decimal('contract_amt',15,2)->default(0.00);
            $table->decimal('exchange_rate',15,4)->default(0.00);
            $table->longText('description')->nullable();
            $table->char('status',2)->default('CR')->comment('CR=Created AP=Approved RJ=Rejected DL Delivered IC Invoice Created IA Invoice Approved DA Delivery Approved');
            $table->date('approve_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('RESTRICT');
            $table->boolean('payment')->default(0)->comment('0=Unpaid 1=Paid');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->string('old_no',120)->nullable();
            $table->string('extra_field',120)->nullable();
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
        Schema::dropIfExists('export_contracts');
    }
}
