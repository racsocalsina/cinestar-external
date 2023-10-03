<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketOfficePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_promotions', function (Blueprint $table) {
            $table->id();
            $table->string('remote_id');
            $table->string('name');
            $table->integer('cant_tickets');
            $table->string('rate_type');
            $table->double('price_second_ticket');
            $table->double('discount');
            $table->string('product_code');
            $table->double('price_ticket');
            $table->double('price_product');
            $table->string('payment_methods');
            $table->string('membership_card_required');
            $table->boolean('is_block_3d');
            $table->boolean('is_block_1s');
            $table->date('start_date');
            $table->date('expiration_date');
            $table->boolean('is_block_sunday');
            $table->boolean('is_block_monday');
            $table->boolean('is_block_tuesday');
            $table->boolean('is_block_wednesday');
            $table->boolean('is_block_thursday');
            $table->boolean('is_block_friday');
            $table->boolean('is_block_saturday');
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
        Schema::dropIfExists('ticket_promotions');
    }
}
