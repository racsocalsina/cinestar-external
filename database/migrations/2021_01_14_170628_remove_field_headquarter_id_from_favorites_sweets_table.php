<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldHeadquarterIdFromFavoritesSweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_combo_favorite', function (Blueprint $table) {
            $table->dropForeign('customer_combo_favorite_headquarter_id_foreign');
            $table->dropColumn('headquarter_id');
        });

        Schema::table('customer_product_favorite', function (Blueprint $table) {
            $table->dropForeign('customer_product_favorite_headquarter_id_foreign');
            $table->dropColumn('headquarter_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorites_sweets', function (Blueprint $table) {
            //
        });
    }
}
