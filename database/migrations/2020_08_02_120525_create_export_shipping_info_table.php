<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportShippingInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_shipping_info', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('invoice_no',false)->unsigned();
            $table->foreign('invoice_no')->references('invoice_no')->on('sales')->onDelete('CASCADE');
            $table->bigInteger('challan_no',false)->unsigned();
            $table->foreign('challan_no')->references('challan_no')->on('deliveries')->onDelete('CASCADE');
            $table->string('vessel_no',120)->nullable();
            $table->date('shipping_date');
            $table->string('shipping_ref',60)->nullable();
            $table->string('packing',150)->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->index('invoice_no');
            $table->index('challan_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_shipping_info');
    }
}
