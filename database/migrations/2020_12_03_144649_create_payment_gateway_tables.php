<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewayTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway_info', function (Blueprint $table) {
            $table->id();
            $table->string('payment_gateway_name', 15);
            $table->unsignedBigInteger('purchase_id');
            $table->string('document_type', 2);
            $table->string('document_number', 20);
            $table->string('name', 50);
            $table->string('lastname', 50);
            $table->string('email', 50);
            $table->string('voucher_type', 2);
            $table->string('ruc', 20)->nullable();
            $table->string('business_name', 50)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('phone', 20 )->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases');
        });

        Schema::create('payment_gateway_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_gateway_info_id');
            $table->longText('response');
            $table->timestamps();

            $table->foreign('payment_gateway_info_id')
                ->references('id')
                ->on('payment_gateway_info');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
}
