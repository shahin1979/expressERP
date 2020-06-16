<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('RESTRICT');
            $table->bigInteger('ref_no',false)->unsigned();
            $table->string('contra_ref',160)->nullable()->comment('purchase l/c #');
            $table->string('invoice_no',160)->nullable()->comment('purchase Invoice #');
            $table->char('purchase_type',2)->comment('LP = Local Purchase, IM = Import BB=b2b l/c LC=Leter of Credit IV=Invoice'); //1 for consumption 2 for purchase
            $table->date('po_date');
            $table->date('invoice_date');
            $table->decimal('invoice_amt',15,2)->default(0.00);
            $table->decimal('paid_amt',15,2)->default(0.00);
            $table->char('discount_type',1)->default('F')->comment('F=Fixed P=Percentage');
            $table->decimal('discount',15,2)->default(0.00);
            $table->decimal('discount_amt',15,2)->default(0.00);
            $table->decimal('due_amt',15,2)->default(0.00);
            $table->bigInteger('authorized_by')->unsigned()->nullable();
            $table->foreign('authorized_by')->references('id')->on('users')->onDelete('restrict');
            $table->string('description')->nullable();
            $table->char('status',2)->default('CR')->comment('CR = created, AP= approved, RC= received, PR= purchased,  DL=delivered, RJ= rejected, RT=>Returned CL=closed');
            $table->string('old_number',12)->nullable();
            $table->string('extra_field',150)->nullable();
            $table->char('stock_status',1)->default('A')->comment('A = Available, F=FINISHED');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes(); // <-- This will add a deleted_at field
            $table->index('company_id');
            $table->unique(array('company_id', 'ref_no'));
            $table->index('ref_no');
            $table->index('po_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
