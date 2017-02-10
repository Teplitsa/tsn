<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegistredFlatIdToVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
           $table->integer('registered_flat_id')->unsigned();
           $table->foreign('registered_flat_id')->references('id')->on('registered_flats');

            $table->dropForeign('votes_user_id_foreign');
            $table->dropColumn('user_id');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->dropForeign('votes_registered_flat_id_foreign');
            $table->dropColumn('registered_flat_id');
        });
    }
}
