<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPointsFieldsToPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->integer('points')->after('remote_movkey')->default(0);
        });

        Schema::table('purchase_tickets', function (Blueprint $table) {
            $table->integer('points')->after('remote_movkey')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
}
