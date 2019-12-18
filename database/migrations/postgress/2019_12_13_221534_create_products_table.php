<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('name',160);
            $table->unsignedBigInteger('product_code');
            $table->unique(array('company_id', 'product_code'));
//            $table->integer('relationship_id')->unsigned()->nullable()->index('FK_products_relationships');
//            $table->foreign('relationship_id')->references('id')->on('relationships')->onDelete('CASCADE');
            $table->integer('brand_id')->unsigned()->nullable();
            $table->foreign('brand_id')->references('id')->on('item_brands')->onDelete('CASCADE');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('CASCADE');
            $table->integer('subcategory_id')->unsigned()->nullable();
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete('CASCADE');
            $table->boolean('multi_unit')->default(0);
            $table->string('unit_name',10);
            $table->foreign('unit_name')->references('name')->on('item_units')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('second_unit',10)->nullable();
            $table->foreign('second_unit')->references('name')->on('item_units')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('third_unit',10)->nullable();
            $table->foreign('third_unit')->references('name')->on('item_units')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->boolean('variants')->default(0);
            $table->integer('size_id')->unsigned()->nullable();
            $table->foreign('size_id')->references('id')->on('item_sizes')->onDelete('CASCADE');
            $table->integer('color_id')->unsigned()->nullable();
            $table->foreign('color_id')->references('id')->on('item_colors')->onDelete('CASCADE');
            $table->string('sku', 50)->nullable();
            $table->unique(array('company_id','sku'));
            $table->integer('model_id')->unsigned()->nullable();
            $table->foreign('model_id')->references('id')->on('item_models')->onDelete('CASCADE');
            $table->integer('tax_id')->unsigned()->nullable();
            $table->foreign('tax_id')->references('id')->on('item_taxes')->onDelete('CASCADE');
            $table->integer('godown_id')->unsigned()->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns')->onDelete('CASCADE');
            $table->integer('rack_id')->unsigned()->nullable();
            $table->foreign('rack_id')->references('id')->on('racks')->onDelete('CASCADE');
            $table->decimal('initial_price',15,2)->default(0.00);
            $table->decimal('buy_price',15,2)->default(0.00);
            $table->decimal('wholesale_price',15,2)->default(0.00);
            $table->decimal('retail_price',15,2)->default(0.00);
            $table->decimal('unit_price',15,2)->default(0.00);
            $table->decimal('reorder_point',15,2)->nullable()->default(0);
            $table->decimal('opening_qty',15,2)->default(0);
            $table->decimal('opening_qty_unit_two',15,2)->default(0);
            $table->decimal('opening_qty_unit_three',15,2)->default(0);
            $table->decimal('opening_value',15,2)->default(0);
            $table->decimal('on_hand',15,2)->default(0);
            $table->decimal('on_hand_unit_two',15,2)->default(0);
            $table->decimal('on_hand_unit_three',15,2)->default(0);
            $table->date('expiry_date')->nullable();
            $table->decimal('committed',15,2)->default(0);
            $table->decimal('in_comming',15,2)->default(0);
            $table->decimal('max_online_stock',15,2)->default(0);
            $table->decimal('min_online_order',15,2)->default(0);
            $table->decimal('purchase_qty',15,2)->default(0);
            $table->decimal('purchase_qty_unit_two',15,2)->default(0);
            $table->decimal('purchase_qty_unit_three',15,2)->default(0);
            $table->decimal('sell_qty',15,2)->default(0);
            $table->decimal('sell_qty_unit_two',15,2)->default(0);
            $table->decimal('sell_qty_unit_three',15,2)->default(0);
            $table->decimal('salvage_qty',15,2)->default(0);
            $table->decimal('salvage_qty_unit_two',15,2)->default(0);
            $table->decimal('salvage_qty_unit_three',15,2)->default(0);
            $table->decimal('received_qty',15,2)->default(0);
            $table->decimal('received_qty_unit_two',15,2)->default(0);
            $table->decimal('received_qty_unit_three',15,2)->default(0);
            $table->decimal('return_qty',15,2)->default(0);
            $table->decimal('return_qty_unit_two',15,2)->default(0);
            $table->decimal('return_qty_unit_three',15,2)->default(0);
            $table->integer('shipping')->unsigned()->nullable()->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->text('description_short')->nullable();
            $table->text('description_long')->nullable();
            $table->text('stuff_included')->nullable();
            $table->float('warranty_period', 10, 0)->unsigned()->nullable();
            $table->string('image',195)->nullable();
            $table->string('image_large',195)->nullable();
            $table->boolean('sellable')->default(true);
            $table->boolean('purchasable')->default(true);
            $table->boolean('b2bpublish')->default(false);
            $table->boolean('free')->unsigned()->default(0);
            $table->boolean('taxable')->unsigned()->default(1);
            $table->boolean('status')->unsigned()->default(1);
            $table->string('locale',20)->default('en-US')->comments('English, Bangla');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes(); // <-- This will add a deleted_at field
            $table->index('company_id');
            $table->index('category_id');
            $table->index('subcategory_id');
            $table->index('name');
            $table->index('unit_name');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
