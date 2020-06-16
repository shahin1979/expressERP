<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('fiscal_year',9);
            $table->string('name');
            $table->decimal('current_year_budget',15,2)->nullable()->default(0);
            $table->decimal('budget_01',15,2)->nullable()->default(0);
            $table->decimal('budget_02',15,2)->nullable()->default(0);
            $table->decimal('budget_03',15,2)->nullable()->default(0);
            $table->decimal('budget_04',15,2)->nullable()->default(0);
            $table->decimal('budget_05',15,2)->nullable()->default(0);
            $table->decimal('budget_06',15,2)->nullable()->default(0);
            $table->decimal('budget_07',15,2)->nullable()->default(0);
            $table->decimal('budget_08',15,2)->nullable()->default(0);
            $table->decimal('budget_09',15,2)->nullable()->default(0);
            $table->decimal('budget_10',15,2)->nullable()->default(0);
            $table->decimal('budget_11',15,2)->nullable()->default(0);
            $table->decimal('budget_12',15,2)->nullable()->default(0);
            $table->boolean('status')->default(true);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->unique(array('company_id', 'name'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cost_centers');
    }
}
