<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerHeadquarterFavoriteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_headquarter_favorite', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('headquarter_id');
            $table->unsignedBigInteger('customer_id');

            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
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
        Schema::dropIfExists('customer_headquarter_favorite');
    }
}
