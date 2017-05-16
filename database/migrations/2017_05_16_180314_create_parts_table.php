<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('person_id')->unsigned();
            $table->foreign('person_id')->references('id')->on('cases')->onDelete('cascade');

            $table->integer('khatma_id')->unsigned();
            $table->foreign('khatma_id')->references('id')->on('khatma')->onDelete('cascade');

            $table->integer('number_of_part');
            $table->integer('start_page');
            $table->integer('end_page');
            $table->integer('current_page');
            $table->string('name_en');
            $table->string('name_ar');
            $table->boolean('taken')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
