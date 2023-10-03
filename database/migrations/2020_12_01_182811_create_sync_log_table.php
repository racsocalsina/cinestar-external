<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyncLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('headquarter_id');
            $table->string('status', 20)->default('syncing');
            $table->dateTime('sync_start_datetime');
            $table->dateTime('sync_end_datetime')->nullable();

            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sync_logs');
    }
}
