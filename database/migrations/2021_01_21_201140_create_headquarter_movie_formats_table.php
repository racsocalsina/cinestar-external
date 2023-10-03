<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadquarterMovieFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headquarter_movie_formats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('headquarter_id');
            $table->unsignedBigInteger('movie_format_id');

            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');
            $table->foreign('movie_format_id')
                ->references('id')
                ->on('movie_formats')
                ->onDelete('cascade');
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
        Schema::dropIfExists('headquarter_movie_formats');
    }
}
