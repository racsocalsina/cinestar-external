<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePlannerGrapthToMovieTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movie_times', function (Blueprint $table) {
            $table->renameColumn('planner_grapth', 'planner_graph');
        });
        Schema::table('movie_times', function (Blueprint $table) {
            $table->text('planner_graph')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movie_times', function (Blueprint $table) {
            $table->renameColumn('planner_graph', 'planner_grapth');
        });
        Schema::table('movie_times', function (Blueprint $table) {
            $table->json('planner_grapth')->change();
        });
    }
}
