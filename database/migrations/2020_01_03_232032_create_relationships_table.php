<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relationships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('RESTRICT');
            $table->char('relation_type',2)->comment('LS=>Local Suppliers CS=>Credit Sale');
            $table->string('name',160);
            $table->unique(array('company_id', 'name'));
            $table->string('tax_number',60)->nullable();
            $table->string('email',120)->nullable();
            $table->string('ledger_acc_no',8)->nullable();
            $table->string('street',200)->nullable();
            $table->string('address',200)->nullable();
            $table->string('city',200)->nullable();
            $table->string('state',200)->nullable();
            $table->string('country',200)->nullable();
            $table->string('zip_code',200)->nullable();
            $table->string('phone_number',200)->nullable();
            $table->string('fax_number',200)->nullable();
            $table->string('website',200)->nullable();
            $table->string('assigned',200)->nullable();
            $table->string('default_price',20)->nullable()->comment('Whole Sale / Retail');
            $table->integer('default_discount',false)->unsigned()->default(0);
            $table->string('default_payment_term',60)->nullable();
            $table->string('default_payment_method',60)->nullable();
            $table->decimal('min_order_value',15,2)->default(0);
            $table->boolean('status')->default(true);
            $table->string('locale',20)->default('en-US')->comments('English, Bangla');
            $table->string('old_id',8)->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes(); // <-- This will add a deleted_at field
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relationships');
    }
}
