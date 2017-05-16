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
            $table->foreign('person_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('khatma_id')->unsigned();
            $table->foreign('khatma_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('part_number');
            $table->integer('start_page');
            $table->integer('end_page');
            $table->integer('current_page');
            $table->string('name');
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
