<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricesToProductsAndCombos3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->double('price', 8,2)->nullable();
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->double('price', 8,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->integer('price')->nullable();
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->integer('price')->nullable();
        });
    }
}
