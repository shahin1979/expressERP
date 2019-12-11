<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE');
            $table->string('name', 50);
            $table->string('manufacturer',120)->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('status')->default(true);
            $table->string('locale',20)->default('en-US')->comments('English, Bangla');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        DB::unprepared('
            CREATE OR REPLACE TRIGGER tr_item_brands_updated_at BEFORE INSERT OR UPDATE ON item_brands FOR EACH ROW
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
        Schema::dropIfExists('item_brands');
    }
}
