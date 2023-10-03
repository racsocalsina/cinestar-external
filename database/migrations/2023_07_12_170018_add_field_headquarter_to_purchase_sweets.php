<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldHeadquarterToPurchaseSweets extends Migration
{
      /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->unsignedBigInteger('headquarter_id')->nullable()->after('remote_movkey');
            $table->unique(['headquarter_id', 'remote_movkey'], 'purchase_sweets_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_sweets', function (Blueprint $table) {
            $table->dropUnique('purchase_sweets_unique_index');
            $table->dropColumn('headquarter_id');
        });
    }
}
