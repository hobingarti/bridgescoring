<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdJenisByeToPertandingans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pertandingans', function (Blueprint $table) {
            //
            $table->integer('id_jenis_bye');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pertandingans', function (Blueprint $table) {
            //
            $table->dropColumn('id_jenis_bye');
        });
    }
}
