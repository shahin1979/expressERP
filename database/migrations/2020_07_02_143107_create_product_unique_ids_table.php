<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductUniqueIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_unique_ids', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('purchase_ref_id',false)->unsigned()->nullable()->comment('PO id');;
            $table->foreign('purchase_ref_id')->references('id')->on('purchases')->onDelete('CASCADE');
            $table->bigInteger('receive_ref_id',false)->unsigned()->nullable()->comment('receive id');;
            $table->foreign('receive_ref_id')->references('id')->on('receives')->onDelete('CASCADE');
            $table->bigInteger('history_ref_id',false)->unsigned()->nullable()->comment('product_histories id');;
            $table->foreign('history_ref_id')->references('id')->on('product_histories')->onDelete('CASCADE');
            $table->bigInteger('return_ref_id',false)->unsigned()->nullable()->comment('return id');;
            $table->foreign('return_ref_id')->references('id')->on('returns')->onDelete('CASCADE');
            $table->bigInteger('sales_ref_id',false)->unsigned()->nullable()->comment('invoice id');;
            $table->foreign('sales_ref_id')->references('id')->on('sales')->onDelete('CASCADE');
            $table->bigInteger('delivery_ref_id',false)->unsigned()->nullable()->comment('Delivery id');;
            $table->foreign('delivery_ref_id')->references('id')->on('deliveries')->onDelete('CASCADE');
            $table->string('temp_id',10)->nullable();
            $table->bigInteger('product_id')->unsigned()->comment('product');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->string('unique_id',100)->unique()->comment('unique id');
            $table->boolean('stock_status')->default(false);
            $table->char('status',1)->default('P')->comment('P=Purchased R=Received T=Returned S=Sold D=delivered');
            $table->boolean('data_validity')->default(false);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->index('company_id');
            $table->index('product_id');
            $table->index('status');
            $table->index(['company_id','unique_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_unique_ids');
    }
}
