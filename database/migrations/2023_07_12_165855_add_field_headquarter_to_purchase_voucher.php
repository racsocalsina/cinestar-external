<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldHeadquarterToPurchaseVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_voucher', function (Blueprint $table) {
            $table->unsignedBigInteger('headquarter_id')->nullable()->after('document_number');
            $table->unique(['headquarter_id', 'internal_serial_number', 'document_number'], 'purchase_voucher_unique_index');
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
            $table->dropUnique('purchase_voucher_unique_index');
            $table->dropColumn('headquarter_id');
        });
    }
    
}
