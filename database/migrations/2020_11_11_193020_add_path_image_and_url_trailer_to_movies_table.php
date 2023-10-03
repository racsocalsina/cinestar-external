<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPathImageAndUrlTrailerToMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('exclude_igv');
            $table->dropColumn('exclude_city_tax');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropForeign(['movie_format_id']);
            $table->dropColumn('movie_format_id');
            $table->string('url_trailer')->after('image_path');
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
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('url_trailer');
            $table->string('exclude_igv');
            $table->string('exclude_city_tax');
            $table->date('start_date');
            $table->date('end_date');
        });
    }
}
