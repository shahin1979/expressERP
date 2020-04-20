<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('RESTRICT');
            $table->bigInteger('challan_no',false)->unsigned();
            $table->bigInteger('ref_no',false)->unsigned()->comment('invoice no');
            $table->bigInteger('relationship_id')->unsigned()->comment('ID from the relationship table');
            $table->char('delivery_type',2)->comment('SL = Sales, EX= Export RT=Return'); //1 for consumption 2 for purchase
            $table->date('delivery_date');
            $table->bigInteger('approve_by')->unsigned()->nullable();
            $table->foreign('approve_by')->references('id')->on('users')->onDelete('restrict');
            $table->date('approve_date')->nullable();
            $table->string('description')->nullable();
            $table->char('status',2)->default(1)->comment('CR = created, AP= approved, RC= received, DL=delivered, RJ= rejected, RT=>Returned CL=closed');
            $table->char('stock_status',1)->default('A')->comment('A = Available, F=FINISHED');
            $table->boolean('direct_delivery')->default(0)->comment('1=> auto delivered after approving the invoice');
            $table->boolean('account_post')->default(0);
            $table->bigInteger('account_voucher',false)->nullable()->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes(); // <-- This will add a deleted_at field
            $table->string('extra_field',150)->nullable();
            $table->string('old_challan',15)->nullable();
            $table->index('company_id');
            $table->unique(array('company_id', 'challan_no'));
            $table->index('challan_no');
            $table->index('delivery_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
