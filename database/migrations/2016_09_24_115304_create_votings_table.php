<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number');
            $table->string('name');
            $table->string('kind');

            $table->integer('house_id')->unsigned();
            $table->foreign('house_id')->references('id')->on('houses');

            $table->integer('voting_type_id')->unsigned();
            $table->foreign('voting_type_id')->references('id')->on('voting_types');

            $table->dateTime('public_at');
            $table->integer('public_length');
            $table->dateTime('opened_at');
            $table->dateTime('closed_at');
            $table->dateTime('protocol_at q');


            $table->integer('public_house_id')->unsigned();
            $table->foreign('public_house_id')->references('id')->on('houses');


            $table->text('election_place');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votings');
    }
}
