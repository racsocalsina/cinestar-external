<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndexToImproveQueryPerfomance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unique('code', 'products_code_unique');
        });

        Schema::table('product_types', function (Blueprint $table) {
            $table->unique('code', 'product_types_code_unique');
        });

        Schema::table('choco_awards', function (Blueprint $table) {
            $table->unique('code', 'choco_awards_code_unique');
        });

        Schema::table('choco_promotions', function (Blueprint $table) {
            $table->unique('code', 'choco_promotions_code_unique');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unique('socio_cod', 'customers_socio_cod_unique');
        });

        Schema::table('movie_times', function (Blueprint $table) {
            $table->unique('remote_funkey', 'movie_times_remote_funkey_unique');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->unique('code', 'movies_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_code_unique');
        });

        Schema::table('product_types', function (Blueprint $table) {
            $table->dropUnique('product_types_code_unique');
        });

        Schema::table('choco_awards', function (Blueprint $table) {
            $table->dropUnique('choco_awards_code_unique');
        });

        Schema::table('choco_promotions', function (Blueprint $table) {
            $table->dropUnique('choco_promotions_code_unique');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_socio_cod_unique');
        });

        Schema::table('movie_times', function (Blueprint $table) {
            $table->dropUnique('movie_times_remote_funkey_unique');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropUnique('movies_code_unique');
        });
    }
}
