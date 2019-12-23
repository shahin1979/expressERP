<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->bigInteger('ref_no')->unsigned();
            $table->char('req_type',1)->comments('P = Purchase, C = Consumption'); //1 for consumption 2 for purchase
            $table->date('req_date');
            $table->integer('requisition_for')->unsigned()->comment('From Location Table Type F');
            $table->foreign('requisition_for')->references('id')->on('locations')->onDelete('CASCADE');
            $table->integer('authorized_by')->unsigned()->nullable();
            $table->foreign('authorized_by')->references('id')->on('users')->onDelete('CASCADE');
            $table->string('description')->nullable();

            $table->tinyInteger('status',false)->unsigned()->default(1)->comments('1 = created, 2= approved, 3= received, 4= purchased,  5=delevered, 6= rejected, 7=closed');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes(); // <-- This will add a deleted_at field
        });

        DB::unprepared('
            CREATE OR REPLACE TRIGGER tr_requisitions_updated_at BEFORE INSERT OR UPDATE ON requisitions FOR EACH ROW
            BEGIN
                :NEW.updated_at := SYSDATE;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisitions');
    }
}
