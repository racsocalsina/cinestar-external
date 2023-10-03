<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditRemoteKeyAndSendInternalToPurchaseTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_tickets', function (Blueprint $table) {
            $table->string('remote_movkey', 10)->nullable()->change();
            $table->string('send_internal', 10)->nullable()->change();
        });

        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->string('remote_movkey', 10)->nullable()->change();
            $table->string('send_internal', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_tickets', function (Blueprint $table) {
            $table->string('remote_movkey', 10)->nullable(false)->change();
            $table->string('send_internal', 10)->nullable(false)->change();
        });

        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->string('remote_movkey', 10)->nullable()->change();
            $table->string('send_internal', 10)->nullable()->change();
        });
    }
}
