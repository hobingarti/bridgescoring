<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPointPropertyonBoard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            //
            $table->integer('point_ns')->nullable()->change();
            $table->integer('point_ew')->nullable()->change();
            $table->renameColumn('score_us', 'score_ns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            //
            $table->integer('point_ns')->change();
            $table->integer('point_ew')->change();
            $table->renameColumn('score_ns', 'score_us');
        });
    }
}
