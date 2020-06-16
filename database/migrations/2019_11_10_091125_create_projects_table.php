<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('project_code',100);
            $table->string('project_type',20)->nullable();
            $table->string('project_name',60);
            $table->longText('project_desc')->nullable();
            $table->string('project_ref',60)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->char('status')->default('P')->comment('P-Proposed, O-On Going, C-Closed, F-Finished');
            $table->decimal('budget',15,2)->default(0);
            $table->decimal('expense',15,2)->default(0);
            $table->decimal('income',15,2)->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->unique(array('company_id', 'project_name'));


        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
//        DB::unprepared('DROP TRIGGER TR_PROJECTS_UPDATED_AT');
    }
}
