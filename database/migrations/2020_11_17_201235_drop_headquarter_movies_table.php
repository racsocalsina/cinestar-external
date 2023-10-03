<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropHeadquarterMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movie_times', function (Blueprint $table) {
            $table->dropForeign(['headquarter_movie_id']);
            $table->dropColumn('headquarter_movie_id');
        });
        Schema::dropIfExists('headquarter_movies');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headquarter_movies', function (Blueprint $table) {
            //
        });
    }
}
