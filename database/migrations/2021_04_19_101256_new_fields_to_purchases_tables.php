<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFieldsToPurchasesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->date('pickup_date')->nullable()->after('total');
        });

        Schema::table('purchase_tickets', function (Blueprint $table) {
            $table->datetime('function_date')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases_tables', function (Blueprint $table) {
            //
        });
    }
}
