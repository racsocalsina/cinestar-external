<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldReferenceCodeToPaymentGatewayInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('ticket_office_promos', 'ticket_promotions');

        Schema::table('payment_gateway_info', function (Blueprint $table) {
            $table->string('reference_code', 30)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_gateway_info', function (Blueprint $table) {
            //
        });
    }
}
