<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStmtLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stmt_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('RESTRICT');
            $table->char('file_no',4);
            $table->integer('line_no',false)->default(0);
            $table->integer('text_position',false)->default(0);
            $table->integer('font_size',false)->default(10);
            $table->string('texts',40);
            $table->string('acc_type',1)->nullable();
            $table->string('bal_type',3)->nullable();
            $table->char('note',2)->nullable();
            $table->string('ac11',9)->nullable();
            $table->string('ac12',9)->nullable();
            $table->string('ac21',9)->nullable();
            $table->string('ac22',9)->nullable();
            $table->integer('figure_position',false)->nullable()->default(0);
            $table->char('sub_total',1)->nullable()->default(null);
            $table->string('formula',51)->nullable()->default(null);
            $table->decimal('range_val1',15,2)->nullable()->default(0);
            $table->decimal('print_val1',15,2)->nullable()->default(0);
            $table->decimal('range_val2',15,2)->nullable()->default(0);
            $table->decimal('print_val2',15,2)->nullable()->default(0);
            $table->decimal('range_val3',15,2)->nullable()->default(0);
            $table->decimal('print_val3',15,2)->nullable()->default(0);
            $table->decimal('print_val',15,2)->nullable()->default(0);
            $table->decimal('pcnt',15,2)->nullable()->default(0);
            $table->boolean('import_line')->default(0);
            $table->boolean('negative_value')->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stmt_lines');
    }
}
