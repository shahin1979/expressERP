<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('RESTRICT');
            $table->bigInteger('invoice_no',false)->unsigned();
            $table->bigInteger('customer_id')->unsigned()->comment('ID from the relationship table');
            $table->char('invoice_type',2)->comment('MI = Cash Sale, CI= Credit Invoice, EX=Export'); //1 for consumption 2 for purchase
            $table->date('invoice_date');
            $table->decimal('invoice_amt',15,2)->default(0.00);
            $table->decimal('paid_amt',15,2)->default(0.00);
            $table->char('discount_type',1)->default('F')->comment('F=Fixed P=Percentage');
            $table->decimal('discount',15,2)->default(0.00);
            $table->decimal('discount_amt',15,2)->default(0.00);
            $table->decimal('due_amt',15,2)->default(0.00);
            $table->unsignedBigInteger('export_contract_id')->nullable();
            $table->decimal('fc_amt',15,2)->default(0.00);
            $table->string('currency',3)->default('USD');
            $table->decimal('exchange_rate',10,4)->default(0.00);
            $table->unsignedBigInteger('importer_bank_id')->nullable();
            $table->unsignedBigInteger('exporter_bank_id')->nullable();
            $table->longText('loading_port')->nullable();
            $table->longText('destination_port')->nullable();
            $table->longText('applicants_bin')->nullable();
            $table->longText('color')->nullable();
            $table->longText('description')->nullable();
            $table->char('status',2)->default(1)->comment('CR = created, AP= approved, RC= received, DL=delivered, RJ= rejected, RT=>Returned CL=closed');
            $table->char('stock_status',1)->default('A')->comment('A = Available, F=FINISHED');
            $table->longText('delivery_terms')->nullable()->comment('for export invoice'); //
            $table->unsignedBigInteger('delivery_challan_id',false)->nullable();
            $table->boolean('direct_delivery')->default(0)->comment('1=> auto delivered after approving the invoice');
            $table->boolean('delivery_status')->default(0);
            $table->boolean('shipment_status')->default(0);
            $table->bigInteger('authorized_by')->unsigned()->nullable();
            $table->foreign('authorized_by')->references('id')->on('users')->onDelete('restrict');
            $table->date('authorized_date')->nullable();
            $table->boolean('account_post')->default(0);
            $table->bigInteger('account_voucher',false)->nullable()->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->string('extra_field',150)->nullable();
            $table->string('old_invoice_no',150)->nullable();
            $table->softDeletes(); // <-- This will add a deleted_at field
            $table->index('company_id');
            $table->unique(array('company_id', 'invoice_no'));
            $table->index('invoice_no');
            $table->index('invoice_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
