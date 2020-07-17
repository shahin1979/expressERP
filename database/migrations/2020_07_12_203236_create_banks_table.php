<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->char('bank_type',1)->comment('I=Importer Bank, B=Buyer Bank E=Exporter Bank S=>Supplier Bank C = Company Bank D=Document Bank');
            $table->char('bank_code',4)->nullable();
            $table->string('bank_name',120);
            $table->string('branch_name',190);
            $table->string('bank_acc_name',190);
            $table->string('bank_acc_no',30);
            $table->unsignedBigInteger('related_gl_id')->nullable();
            $table->foreign('related_gl_id')->references('id')->on('general_ledgers')->onDelete('CASCADE');
            $table->longText('address')->nullable();
            $table->string('swift_code',120)->nullable();
            $table->string('mobile_no',120)->nullable();
            $table->string('email',120)->nullable();
            $table->string('extra_field',120)->nullable();
            $table->boolean('status')->default(1);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
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
        Schema::dropIfExists('banks');
    }
}
