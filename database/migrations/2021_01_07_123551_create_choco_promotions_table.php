<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChocoPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choco_promotions', function (Blueprint $table) {
            $table->id();
            $table->string('remote_id');
            $table->string('name');
            $table->date('start_date');
            $table->date('expiration_date');
            $table->double('discount');
            $table->boolean('membership_card_required');
            $table->unsignedBigInteger('type_payment_method_id')->nullable();
            $table->foreign('type_payment_method_id')
                ->references('id')
                ->on('types_payment_method');
            $table->unsignedBigInteger('headquarter_id')->nullable();
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('choco_promotions');
    }
}
