<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullableColumnsToPurchaseVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_voucher', function (Blueprint $table) {
            $table->string('purchase_order_number', 10)->nullable()->change();
            $table->string('external_id', 50)->nullable()->change();
            $table->string('hash', 50)->nullable()->change();
            $table->text('qr_code')->nullable()->change();
            $table->string('link_xml', 150)->nullable()->change();
            $table->string('link_pdf', 150)->nullable()->change();
            $table->string('link_cdr', 150)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_voucher', function (Blueprint $table) {
            $table->string('purchase_order_number', 10)->nullable(false)->change();
            $table->string('external_id', 50)->nullable(false)->change();
            $table->string('hash', 50)->nullable(false)->change();
            $table->text('qr_code')->nullable(false)->change();
            $table->string('link_xml', 150)->nullable(false)->change();
            $table->string('link_pdf', 150)->nullable(false)->change();
            $table->string('link_cdr', 150)->nullable(false)->change();
        });
    }
}
