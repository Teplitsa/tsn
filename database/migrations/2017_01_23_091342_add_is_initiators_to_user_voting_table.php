<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsInitiatorsToUserVotingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_voting', function (Blueprint $table) {
            $table->boolean('is_initiator')->default(0);
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
        Schema::table('user_voting', function (Blueprint $table) {
            $table->dropColumn('is_initiator');
            $table->dropTimestamps();
        });
    }
}
