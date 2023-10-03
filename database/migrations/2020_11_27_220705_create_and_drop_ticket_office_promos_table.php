<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAndDropTicketOfficePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ticket_promotions');
        Schema::create('ticket_promotions', function (Blueprint $table) {
            $table->id();
            $table->string('remote_cod');
            $table->string('name');
            $table->integer('cant_tickets');
            $table->double('price_second_ticket');
            $table->double('discount');
            $table->string('product_code');
            $table->double('price_ticket');
            $table->double('price_product');
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
            $table->unsignedBigInteger('movie_tariff_id');
            $table->foreign('movie_tariff_id')
                ->references('id')
                ->on('movie_tariffs');
            $table->unsignedBigInteger('types_payment_method_id');
            $table->foreign('types_payment_method_id')
                ->references('id')
                ->on('types_payment_method');
            $table->unsignedBigInteger('headquarter_id');
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters');
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
        Schema::table('ticket_promotions', function (Blueprint $table) {
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
}
