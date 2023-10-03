<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image', 50)->nullable()->change();
        });

        Schema::table('combos', function (Blueprint $table) {
            $table->string('image', 50)->nullable()->change();
        });

        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_type_id')->nullable()->change();
        });

        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->unsignedBigInteger('combo_type_id')->nullable()->change();
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
