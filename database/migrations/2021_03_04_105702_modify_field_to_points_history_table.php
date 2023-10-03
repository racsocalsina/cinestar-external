<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFieldToPointsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cinestar_socios')->table('points_history', function (Blueprint $table) {
            $table->string('remote_movkey', 20)->nullable();
            $table->boolean('available')->default(1);
            $table->dropColumn('purchase_id');
            $table->unsignedBigInteger('purchase_promotion_id')->nullable();
            $table->unsignedInteger('points')->after('increase')->change();

            $table->dropColumn('increase');
            $table->string('type', 15)->after('soccod');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('points_history', function (Blueprint $table) {
            //
        });
    }
}
