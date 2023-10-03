<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRemoteMovkeyFieldFromPurchaseAndSweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_tickets', function (Blueprint $table) {
            $table->string('remote_movkey', 20)->change();
        });

        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->string('remote_movkey', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_and_sweets', function (Blueprint $table) {
            //
        });
    }
}
