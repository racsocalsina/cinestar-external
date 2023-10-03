<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropMovieFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['room_type_id']);
        });

        Schema::table('movie_times', function (Blueprint $table) {
            $table->dropForeign(['movie_format_id']);
            $table->dropColumn(['movie_format_id']);
        });

        Schema::dropIfExists('headquarter_movie_formats');
        Schema::dropIfExists('movie_formats');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
