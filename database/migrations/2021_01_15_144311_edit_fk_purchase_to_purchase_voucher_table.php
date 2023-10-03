<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditFkPurchaseToPurchaseVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_voucher', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_sweet_id')->nullable()->after('purchase_id');
            $table->unsignedBigInteger('purchase_ticket_id')->nullable()->after('purchase_sweet_id');

            $table->foreign('purchase_sweet_id')
                ->references('id')
                ->on('purchase_sweets')
                ->onDelete('cascade');

            $table->foreign('purchase_ticket_id')
                ->references('id')
                ->on('purchase_tickets')
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
            //
        });
    }
}
