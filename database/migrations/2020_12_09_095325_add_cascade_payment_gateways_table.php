<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_transaction', function (Blueprint $table) {
            $table->dropForeign('payment_gateway_transaction_payment_gateway_info_id_foreign');

            $table->foreign('payment_gateway_info_id')
                ->references('id')
                ->on('payment_gateway_info')
                ->onDelete('cascade');
        });

        Schema::table('payment_gateway_info', function (Blueprint $table) {
            $table->dropForeign('payment_gateway_info_purchase_id_foreign');

            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->onDelete('cascade');
        });

        Schema::table('purchase_errors', function (Blueprint $table) {
            $table->dropForeign('purchase_errors_purchase_id_foreign');

            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
