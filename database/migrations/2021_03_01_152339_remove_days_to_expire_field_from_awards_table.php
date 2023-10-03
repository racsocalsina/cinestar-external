<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDaysToExpireFieldFromAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('choco_awards', function (Blueprint $table) {
            $table->dropColumn("days_to_expire");
        });

        Schema::table('ticket_awards', function (Blueprint $table) {
            $table->dropColumn("days_to_expire");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expire_field_from_awards', function (Blueprint $table) {
            //
        });
    }
}
