<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('purchase_item_id');
            $table->unsignedBigInteger('movie_time_tariff_id');
            $table->char('chair_row', 2)->nullable();
            $table->char('chair_column', 2)->nullable();
            $table->smallInteger('planner_index')->nullable();
            $table->string('seat_name', 5)->nullable();

            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->onDelete('cascade');
            $table->foreign('purchase_item_id')
                ->references('id')
                ->on('purchase_items')
                ->onDelete('cascade');
            $table->foreign('movie_time_tariff_id')
                ->references('id')
                ->on('movie_time_tariff')
                ->onDelete('cascade');
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
        Schema::dropIfExists('ticket');
    }
}
