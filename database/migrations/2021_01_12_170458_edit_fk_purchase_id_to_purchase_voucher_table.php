<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditFkPurchaseIdToPurchaseVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_voucher', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
        });

        Schema::table('purchase_voucher', function (Blueprint $table) {
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
        Schema::table('purchase_voucher', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
        });

        Schema::table('purchase_voucher', function (Blueprint $table) {
            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases');
        });
    }
}
