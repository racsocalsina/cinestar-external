<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieTimeTarrifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_time_tarrif', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_time_id');
            $table->unsignedBigInteger('movie_tarrif_id');
            $table->double('amount');
            $table->timestamps();
            $table->foreign('movie_time_id')
                ->references('id')
                ->on('movie_times')
                ->onDelete('cascade');
            $table->foreign('movie_tarrif_id')
                ->references('id')
                ->on('movie_tariffs')
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
        Schema::dropIfExists('movie_time_tarrif');
    }
}
