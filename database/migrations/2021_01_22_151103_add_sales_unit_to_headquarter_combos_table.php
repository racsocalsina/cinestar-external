<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalesUnitToHeadquarterCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->string('sales_unit', 15);
        });
        Schema::table('headquarter_combo', function (Blueprint $table) {
            $table->string('sales_unit', 15);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headquarter_combos', function (Blueprint $table) {
            //
        });
    }
}
