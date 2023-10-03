<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMovieFormatIdToMovieTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movie_times', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_id')->nullable();
            $table->unsignedBigInteger('headquarter_id')->nullable();
            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
                ->onDelete('cascade');
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');
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
            //
        });
    }
}
