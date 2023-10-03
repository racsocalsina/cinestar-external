<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewTablesForChocoAndTicketAwards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_awards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5);
            $table->string('name', 100);
            $table->smallInteger('points');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')
                ->references('id')
                ->on('products');
            $table->unsignedBigInteger('combo_id')->nullable();
            $table->foreign('combo_id')
                ->references('id')
                ->on('combos');
            $table->text('restrictions');
            $table->string('unit', 5);
            $table->smallInteger('days_to_expire');
            $table->timestamps();
        });

        Schema::create('choco_awards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5);
            $table->string('name', 100);
            $table->smallInteger('points');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')
                ->references('id')
                ->on('products');
            $table->unsignedBigInteger('combo_id')->nullable();
            $table->foreign('combo_id')
                ->references('id')
                ->on('combos');
            $table->text('restrictions');
            $table->string('unit', 5);
            $table->smallInteger('days_to_expire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_awards');
        Schema::dropIfExists('choco_awards');
    }
}
