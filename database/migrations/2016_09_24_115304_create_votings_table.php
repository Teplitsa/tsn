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
            $table->string('name');
            $table->integer('house_id')->unsigned();
            $table->foreign('house_id')->references('id')->on('houses');
            $table->dateTime('closed_at');
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
