<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('transaction_status')->nullable()->after('status');
            $table->integer('retries')->default(0)->after('transaction_status');
            $table->json('error_event_history')->nullable()->after('retries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('transaction_status');
            $table->dropColumn('retries');
            $table->dropColumn('error_event_history');
        });
    }
}
