<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricesToProductsAndCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_type_id')->nullable()->change();
            $table->string('remote_price_code', 12);
            $table->integer('price');
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->unsignedBigInteger('combo_type_id')->nullable()->change();
            $table->string('remote_price_code', 12);
            $table->integer('price');
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
            $table->unsignedBigInteger('product_type_id')->nullable(false)->change();
            $table->dropColumn('remote_price_code');
            $table->dropColumn('price');
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->unsignedBigInteger('combo_type_id')->nullable(false)->change();
            $table->dropColumn('remote_price_code');
            $table->dropColumn('price');
        });
    }
}
