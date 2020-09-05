<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportShippingFileUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_shipping_file_upload', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('invoice_id',false)->unsigned();
            $table->foreign('invoice_id')->references('id')->on('sales')->onDelete('CASCADE');
            $table->bigInteger('challan_id',false)->unsigned();
            $table->foreign('challan_id')->references('id')->on('deliveries')->onDelete('CASCADE');
            $table->string('forwarder',120)->nullable();
            $table->string('vessel_no',120)->nullable();
            $table->string('container',120)->nullable();
            $table->string('size',120)->nullable();
            $table->string('seal_no',120)->nullable();
            $table->string('destination',120)->nullable();
            $table->string('booking_no',120)->nullable();
            $table->string('bl_no',120)->nullable();
            $table->string('shipper',120)->nullable();
            $table->string('cbm',120)->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->index('invoice_id');
            $table->index('challan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_shipping_file_upload');
    }
}
