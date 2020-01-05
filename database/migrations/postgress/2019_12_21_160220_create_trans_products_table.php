<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('ref_no',false)->unsigned()->comments('Invoice No, Purchase Order No, Requisition No, Challan N');;
            $table->bigInteger('ref_id',false)->unsigned()->comments('Invoice, Purchase, Requisition No, Challan id');;
            $table->date('tr_date');
            $table->char('ref_type',1)->comments('P = Purchase, R = Requisition, S = Sales, I = Import, D = Delivery, E = Export'); //1 for consumption 2 for purchase

            $table->integer('to_whom')->unsigned()->nullable()->comment('From Location Table Type F')->comments('For which department this was created');
            $table->foreign('to_whom')->references('id')->on('locations')->onDelete('CASCADE');

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->string('name',160)->nullable();
            $table->decimal('quantity',15,2)->default(0.00);
            $table->decimal('unit_price',15,2)->default(0.00);
            $table->integer('tax_id')->unsigned()->nullable();
            $table->foreign('tax_id')->references('id')->on('item_taxes')->onDelete('CASCADE');
            $table->decimal('tax_total',15,2)->default(0.00);
            $table->decimal('total_price',15,2)->default(0.00);
            $table->decimal('approved',15,2)->default(0.00);
            $table->decimal('purchased',15,2)->default(0.00);
            $table->decimal('sold',15,2)->default(0.00);
            $table->decimal('received',15,2)->default(0.00);
            $table->decimal('returned',15,2)->default(0.00);
            $table->decimal('delivered',15,2)->default(0.00);
            $table->string('remarks',190)->nullable();
            $table->tinyInteger('status',false)->unsigned()->default(1)->comments('1 = created, 2= approved, 3= purchased, 4= received, 5=delevered, 6= rejected, 7=closed');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('deleted')->default(false);
            $table->softDeletes(); // <-- This will add a deleted_at field
            $table->index('company_id');
            $table->index('ref_no');
            $table->index('product_id');
            $table->index('tr_date');
            $table->index('ref_id');
            $table->index('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_products');
    }
}