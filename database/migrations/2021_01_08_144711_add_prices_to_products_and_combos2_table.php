<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricesToProductsAndCombos2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->string('remote_price_code', 12)->nullable()->change();
            $table->integer('price')->nullable()->change();
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->string('remote_price_code', 12)->nullable()->change();
            $table->integer('price')->nullable()->change();
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
            $table->string('remote_price_code')->nullable(false)->change();
            $table->integer('price')->nullable(false)->change();
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->string('remote_price_code')->nullable(false)->change();
            $table->integer('price')->nullable(false)->change();
        });
    }
}
