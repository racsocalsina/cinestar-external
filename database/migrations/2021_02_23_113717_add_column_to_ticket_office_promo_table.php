<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTicketOfficePromoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_promotions', function (Blueprint $table) {
            $table->integer('max_num_tickets')->nullable();
            $table->boolean('is_prom_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_promotions', function (Blueprint $table) {
            //
        });
    }
}
