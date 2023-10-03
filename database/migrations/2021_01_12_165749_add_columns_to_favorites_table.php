<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_product_favorite', function (Blueprint $table) {
            $table->unsignedBigInteger('headquarter_id');

            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');
        });

        Schema::table('customer_combo_favorite', function (Blueprint $table) {
            $table->unsignedBigInteger('headquarter_id');

            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
