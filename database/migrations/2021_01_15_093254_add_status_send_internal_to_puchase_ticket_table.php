<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusSendInternalToPuchaseTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_tickets', function (Blueprint $table) {
            $table->string('remote_movkey', 10);
            $table->string('send_internal', 10);
        });

        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->string('remote_movkey', 10);
            $table->string('send_internal', 10);
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
            $table->dropColumn('remote_movkey');
            $table->dropColumn('send_internal');
        });

        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->dropColumn('remote_movkey');
            $table->dropColumn('send_internal');
        });
    }
}
