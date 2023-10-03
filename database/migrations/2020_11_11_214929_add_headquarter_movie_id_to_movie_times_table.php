<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeadquarterMovieIdToMovieTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('movie_times', function (Blueprint $table) {
            $table->dropForeign(['movie_id']);
            $table->dropColumn('movie_id');
            $table->unsignedBigInteger('headquarter_movie_id')->after('room_id');
            $table->date('date_start')->after('start_at');
            $table->time('time_start')->after('date_start');
            $table->foreign('headquarter_movie_id')
                ->references('id')
                ->on('headquarter_movies')
                ->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movie_times', function (Blueprint $table) {
            $table->dropForeign(['headquarter_movie_id']);
            $table->dropColumn('headquarter_movie_id');
            $table->unsignedBigInteger('movie_id')->after('room_id');
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->dropColumn('date_start');
            $table->dropColumn('time_start');
        });
    }
}
