<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('room_id');
            $table->string('remote_funkey');
            $table->string('fun_nro');
            $table->dateTime('start_at');
            $table->boolean('is_presale');
            $table->json('planner_grapth');
            $table->integer('capacity');
            $table->integer('tickets_sold');
            $table->boolean('is_numerated');
            $table->timestamps();
            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
                ->onDelete('cascade');
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
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
        Schema::dropIfExists('movie_times');
    }
}
