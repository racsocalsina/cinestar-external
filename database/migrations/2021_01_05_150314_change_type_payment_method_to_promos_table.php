<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypePaymentMethodToPromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_tariff_id')->nullable()->change();
            $table->unsignedBigInteger('types_payment_method_id')->nullable()->change();
            $table->string('movie_chain')->nullable()->after('headquarter_id');
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
