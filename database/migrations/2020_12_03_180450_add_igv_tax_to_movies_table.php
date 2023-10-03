<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIgvTaxToMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_gender_id')->nullable()->change();
            $table->boolean('exclude_igv')->default(false);
            $table->boolean('exclude_city_tax')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('exclude_igv');
            $table->dropColumn('exclude_city_tax');
            $table->unsignedBigInteger('movie_gender_id')->nullable(false)->change();
        });
    }
}
