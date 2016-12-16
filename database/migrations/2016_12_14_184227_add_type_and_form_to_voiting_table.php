<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAndFormToVoitingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votings', function (Blueprint $table) {
            $table->dropForeign('votings_voting_type_id_foreign');
            $table->dropColumn('voting_type_id');
            $table->dropColumn('public_length');
            $table->dropColumn('number');
            $table->dateTime('protocol_at')->nullable()->change();
            $table->string('protocol_number');
            $table->dateTime('end_at');
            $table->string('voting_type')->nullable();

        });
        Schema::table('vote_items', function (Blueprint $table) {
            $table->string('solution')->nullable()->change();

        });
        Schema::dropIfExists('voting_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('voting_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('votings', function (Blueprint $table) {
            $table->dropColumn('end_at');
            $table->integer('public_length');
            $table->dropColumn('protocol_number');
            $table->integer('number');
            $table->dateTime('protocol_at')->change();
            $table->integer('voting_type_id')->unsigned()->nullable();
            $table->foreign('voting_type_id')->references('id')->on('voting_types');
        });
        Schema::table('vote_items', function (Blueprint $table) {
            $table->string('solution')->change();

        });
    }
}
