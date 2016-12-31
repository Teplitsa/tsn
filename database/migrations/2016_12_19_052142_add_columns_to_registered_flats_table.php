<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToRegisteredFlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registered_flats', function (Blueprint $table) {
            $table->dropColumn('activate_code');

            $table->string('square');
            $table->string('up_part');
            $table->string('down_part');

            $table->string('number_doc');
            $table->date('date_doc');
            $table->string('issuer_doc');

            $table->string('scan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registered_flats', function (Blueprint $table) {
            $table->string('activate_code')->nullable();

            $table->dropColumn('square');
            $table->dropColumn('up_part');
            $table->dropColumn('down_part');
            $table->dropColumn('number_doc');
            $table->dropColumn('date_doc');
            $table->dropColumn('issuer_doc');
            $table->dropColumn('scan');
        });
    }
}
