<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChocoPromotionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choco_promotion_products', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->double('discount');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->unsignedBigInteger('promotion_id');
            $table->foreign('promotion_id')
                ->references('id')
                ->on('choco_promotions');

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
        Schema::dropIfExists('choco_promotion_products');
    }
}
