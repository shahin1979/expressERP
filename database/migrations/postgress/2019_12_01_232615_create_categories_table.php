<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('name', 200);
            $table->unique(array('company_id','name'));
            $table->string('alias', 100)->nullable();
            $table->boolean('has_sub')->default(1);
            $table->string('acc_no',8)->nullable();//GL Head for stock debit credit
            $table->boolean('status')->default(true);
            $table->string('locale',20)->default('en-US')->comments('English, Bangla');
            $table->decimal('inventory_amt',15,2)->default(0)->comment('Current balance * avg unit price'); //Amount calculated by current balance*avg unit proce
            $table->decimal('acc_balance',15,2)->default(0)->comment('General Ledger Balance');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->index('name');
            $table->index('company_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');

    }
}
