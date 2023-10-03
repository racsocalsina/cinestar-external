<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesPaymentMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_payment_method', function (Blueprint $table) {
            $table->id();
            $table->char('remote_code', 3);
            $table->string('name', 45);
            $table->char('type_currency', 1);
            $table->char('payment_type', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types_payment_method');
    }
}
