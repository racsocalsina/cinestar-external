<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToTicketPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_promotions', function (Blueprint $table) {
            $table->dropColumn('product_code');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->boolean('membership_card_required')->nullable();
        });

        Schema::table('choco_promotions', function (Blueprint $table) {
            $table->char('movie_chain', 1)->nullable()->after('headquarter_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_promotions', function (Blueprint $table) {
            //
        });
    }
}
