<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldToChocoPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('choco_promotions', function (Blueprint $table) {
            $table->boolean('is_block_sunday');
            $table->boolean('is_block_monday');
            $table->boolean('is_block_tuesday');
            $table->boolean('is_block_wednesday');
            $table->boolean('is_block_thursday');
            $table->boolean('is_block_friday');
            $table->boolean('is_block_saturday');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('choco_promotions', function (Blueprint $table) {
            //
        });
    }
}
