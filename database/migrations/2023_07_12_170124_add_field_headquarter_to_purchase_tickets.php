<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldHeadquarterToPurchaseTickets extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('headquarter_id')->nullable()->after('remote_movkey');
            $table->unique(['headquarter_id', 'remote_movkey'], 'purchase_tickets_unique_index');
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
            $table->dropUnique('purchase_tickets_unique_index');
            $table->dropColumn('headquarter_id');
        });
    }
}
