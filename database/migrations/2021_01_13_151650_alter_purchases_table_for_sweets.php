<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPurchasesTableForSweets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->decimal('total');
            $table->timestamps();

            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->onDelete('cascade');
        });

        Schema::create('purchase_sweets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->decimal('total');
            $table->timestamps();

            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->onDelete('cascade');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_ticket_id')->nullable();
            $table->unsignedBigInteger('purchase_sweet_id')->nullable();

            $table->foreign('purchase_ticket_id')
                ->references('id')
                ->on('purchase_tickets')
                ->onDelete('cascade');

            $table->foreign('purchase_sweet_id')
                ->references('id')
                ->on('purchase_sweets')
                ->onDelete('cascade');
        });

        Schema::create('sweets_sold', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('purchase_item_id');
            $table->string('sweet_type', 20);
            $table->unsignedBigInteger('sweet_id');
            $table->string('code', 20);
            $table->string('name', 80);
            $table->unsignedBigInteger('type_id');
            $table->string('type_name', 80);
            $table->double('price', 8, 2);

            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->onDelete('cascade');

            $table->foreign('purchase_item_id')
                ->references('id')
                ->on('purchase_items')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        // Add some fields as nullables
        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_id')->nullable()->change();
            $table->unsignedBigInteger('movie_time_id')->nullable()->change();
            $table->unsignedSmallInteger('number_tickets')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sweets_sold');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchase_sweets');
        Schema::dropIfExists('purchase_tickets');

    }
}
