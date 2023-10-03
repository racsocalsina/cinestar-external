<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('headquarter_id');
            $table->unsignedBigInteger('movie_time_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount');
            $table->string('uuid')->nullable();
            $table->string('voucher_type')->nullable();
            $table->string('status');
            $table->unsignedSmallInteger('number_tickets');

            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
                ->onDelete('cascade');
            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters')
                ->onDelete('cascade');
            $table->foreign('movie_time_id')
                ->references('id')
                ->on('movie_times')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('purchases');
    }
}
