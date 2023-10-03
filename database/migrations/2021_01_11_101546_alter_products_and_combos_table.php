<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsAndCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove fks
        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->dropForeign('headquarter_product_product_type_id_foreign');
            $table->dropColumn('product_type_id');
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->dropForeign('headquarter_combo_combo_type_id_foreign');
            $table->dropColumn('combo_type_id');
        });

        // Add fks
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_type_id')->nullable()->after('name');
            $table->foreign('product_type_id')->references('id')->on('product_types');
        });

        Schema::table('combos', function (Blueprint $table) {
            $table->unsignedBigInteger('combo_type_id')->nullable()->after('name');
            $table->foreign('combo_type_id')->references('id')->on('combo_types');
        });
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
