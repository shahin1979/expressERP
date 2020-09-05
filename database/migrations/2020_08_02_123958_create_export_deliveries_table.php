<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_deliveries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('challan_id')->unsigned();
            $table->foreign('challan_id')->references('id')->on('deliveries')->onDelete('CASCADE');
            $table->bigInteger('history_id')->unsigned();
            $table->foreign('history_id')->references('id')->on('product_histories')->onDelete('CASCADE');
            $table->string('container',60)->nullable();
            $table->string('seal_no',60)->nullable();
            $table->decimal('cbm',5,2)->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->index('company_id');
            $table->index('challan_id');
            $table->index('history_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_deliveries');
    }
}
