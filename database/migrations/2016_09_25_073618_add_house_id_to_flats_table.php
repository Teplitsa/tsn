<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHouseIdToFlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flats', function (Blueprint $table) {
            $table->integer('house_id')->unsigned();
            $table->foreign('house_id')->references('id')->on('houses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flats', function (Blueprint $table) {
            $table->dropForeign('flats_house_id_foreign');
            $table->dropColumn('house_id');
        });
    }
}
