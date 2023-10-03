<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('image_path');
            $table->string('summary');
            $table->integer('duration_in_minutes');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('type_of_censorship');
            $table->string('exclude_igv');
            $table->string('exclude_city_tax');
            $table->unsignedBigInteger('movie_gender_id');
            $table->unsignedBigInteger('countrie_id');
            $table->foreign('movie_gender_id')
                ->references('id')
                ->on('movie_genders')
                ->onDelete('cascade');
            $table->foreign('countrie_id')
                ->references('id')
                ->on('countries')
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
        Schema::dropIfExists('movies');
    }
}
