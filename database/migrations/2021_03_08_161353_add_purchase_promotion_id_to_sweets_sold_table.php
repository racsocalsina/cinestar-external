<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchasePromotionIdToSweetsSoldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sweets_sold', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_promotion_id')->nullable();
            $table->foreign('purchase_promotion_id')
                ->references('id')
                ->on('purchase_promotions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sweets_sold', function (Blueprint $table) {
            //
        });
    }
}
