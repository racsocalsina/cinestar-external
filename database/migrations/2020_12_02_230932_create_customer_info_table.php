<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_customer_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_voucher_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('type_document', 1);
            $table->string('document_number', 15);
            $table->string('name', 150);
            $table->string('ubigeo', 6)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('email', 100);
            $table->string('phone', 15)->nullable();
            $table->timestamps();
            $table->foreign('purchase_voucher_id')
                ->references('id')
                ->on('purchase_voucher');
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_info');
    }
}
