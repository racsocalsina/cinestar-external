<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPromotionTypeToPromotionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_promotions', function (Blueprint $table) {
            $table->dropColumn('is_prom_code');
            $table->char('promotion_type', 3);
        });

        Schema::table('choco_promotions', function (Blueprint $table) {
            $table->char('promotion_type', 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotions_tables', function (Blueprint $table) {
            //
        });
    }
}
