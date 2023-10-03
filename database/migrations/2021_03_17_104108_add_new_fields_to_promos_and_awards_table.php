<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToPromosAndAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_promotions', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('image', 50)->nullable();
        });

        Schema::table('choco_promotions', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('image', 50)->nullable();
        });

        Schema::table('ticket_awards', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('image', 50)->nullable();
        });

        Schema::table('choco_awards', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('image', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promos_and_awards', function (Blueprint $table) {
            //
        });
    }
}
