<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeadquarterProductIdToSweetsSoldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sweets_sold', function (Blueprint $table) {
            $table->bigInteger('headquarter_product_id')->after('purchase_item_id');
        });

        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->string('send_fe', 10)->after('points')->nullable();
        });

        Schema::table('purchase_tickets', function (Blueprint $table) {
            $table->string('send_fe', 10)->after('points')->nullable();
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
