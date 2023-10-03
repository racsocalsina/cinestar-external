<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComboToChocoPromotionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('choco_promotion_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->unsignedBigInteger('combo_id')->nullable()->after('product_id');
            $table->foreign('combo_id')
                ->references('id')
                ->on('combos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('choco_promotion_products', function (Blueprint $table) {
            //
        });
    }
}
