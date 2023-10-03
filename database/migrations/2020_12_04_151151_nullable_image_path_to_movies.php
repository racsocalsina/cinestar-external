<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullableImagePathToMovies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->string('image_path')->nullable()->change();
            $table->string('url_trailer')->nullable()->change();
            $table->text('summary')->nullable()->change();
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
            $table->string('url_trailer')->nullable(false)->change();
            $table->string('url_trailer')->nullable(false)->change();
            $table->text('summary')->nullable(false)->change();
        });
    }
}
