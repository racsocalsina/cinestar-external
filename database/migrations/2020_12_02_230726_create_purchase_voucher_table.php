<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_voucher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->string('serial_number', 6);
            $table->timestamp('date_issue');
            $table->string('purchase_order_number', 10);
            $table->string('external_id', 50);
            $table->string('hash', 50);
            $table->text('qr_code');
            $table->string('link_xml', 150);
            $table->string('link_pdf', 150);
            $table->string('link_cdr', 150);
            $table->timestamps();
            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_voucher');
    }
}
