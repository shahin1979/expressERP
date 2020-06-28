<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('ref_no',false)->unsigned()->comment('Challan N');;
            $table->bigInteger('ref_id',false)->unsigned()->comment('Challan id');;
            $table->date('tr_date');
            $table->char('ref_type',1)->comment('P = Purchase, S = Sales, I = Import, D = Delivery, E = Export, R=Return, T=Transform O=Opening W=wastage');
            $table->bigInteger('contra_ref',false)->unsigned()->comment('Invoice No, Purchase Order No, Requisition No');;
            $table->bigInteger('product_id')->unsigned()->comment('product');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->decimal('quantity_in',15,4)->default(0.00);
            $table->decimal('quantity_out',15,4)->default(0.00);
            $table->decimal('unit_price',15,4)->default(0.00);
            $table->decimal('total_price',15,4)->default(0.00);
            $table->json('multi_unit')->nullable();
            $table->bigInteger('relationship_id')->unsigned()->nullable()->comment('For which department/supplier/ buyer etc this was created');
            $table->string('remarks',190)->nullable();
            $table->boolean('status')->unsigned()->default(1)->comment('0 = valid, 1= reversed');
            $table->boolean('acc_post')->default(0);
            $table->decimal('stock_out',15,4)->default(0.00);
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes(); // <-- This will add a deleted_at field
            $table->index('company_id');
            $table->index('ref_no');
            $table->index('product_id');
            $table->index('tr_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_histories');
    }
}
