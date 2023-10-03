<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdFieldToPointHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cinestar_socios')->dropIfExists('points_history');

        Schema::connection('cinestar_socios')->create('points_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('soccod', 13);
            $table->string('type', 15);
            $table->char('sales_type', 1);
            $table->unsignedInteger('points');
            $table->timestamp('created_at')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->date('expiration_date')->nullable();
            $table->boolean('from_erp')->default(1);
            $table->boolean('available')->default(1);
            $table->string('remote_movkey', 20)->nullable();
            $table->unsignedBigInteger('purchase_promotion_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_history', function (Blueprint $table) {
            //
        });
    }
}
