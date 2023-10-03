<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('ticket_promotions', 'restrictions')) {
            Schema::table('ticket_promotions', function (Blueprint $table) {
                $table->dropColumn('restrictions');
            });
        }

        if (Schema::hasColumn('choco_promotions', 'restrictions')) {
            Schema::table('choco_promotions', function (Blueprint $table) {
                $table->dropColumn('restrictions');
            });
        }

        Schema::table('ticket_awards', function (Blueprint $table) {
            $table->text('restrictions')->nullable()->change();
        });

        Schema::table('choco_awards', function (Blueprint $table) {
            $table->text('restrictions')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
