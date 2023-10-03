<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixFieldNameToAwardsPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('choco_promotions', function (Blueprint $table) {
            $table->renameColumn('remote_id', 'code');
            $table->renameColumn('expiration_date', 'end_date');
            $table->renameColumn('discount', 'discount_rate');
        });

        Schema::table('ticket_promotions', function (Blueprint $table) {
            $table->renameColumn('remote_cod', 'code');
            $table->renameColumn('expiration_date', 'end_date');
            $table->renameColumn('discount', 'discount_rate');
            $table->renameColumn('types_payment_method_id', 'type_payment_method_id');
            $table->renameColumn('cant_tickets', 'tickets_number');
        });

        Schema::table('choco_promotion_products', function (Blueprint $table) {
            $table->renameColumn('discount', 'discount_rate');
        });

        Schema::table('ticket_promotions', function (Blueprint $table) {
            $table->smallInteger('promo_tickets_number')->nullable();
            $table->char('tariff_type', 2)->nullable();
        });

        Schema::table('ticket_promotions', function (Blueprint $table) {
            $table->dropForeign('ticket_office_promos_movie_tariff_id_foreign');
            $table->dropColumn('movie_tariff_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
