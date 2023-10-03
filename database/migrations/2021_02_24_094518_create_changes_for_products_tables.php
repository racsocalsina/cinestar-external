<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangesForProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->smallInteger('type')->nullable()->after('name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_combo')->nullable()->after('image');
        });

        Schema::table('ticket_awards', function (Blueprint $table) {
            $table->dropConstrainedForeignId('combo_id');
        });

        Schema::table('choco_awards', function (Blueprint $table) {
            $table->dropConstrainedForeignId('combo_id');
        });

        Schema::table('choco_promotion_products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('combo_id');
        });

        Schema::dropIfExists('headquarter_combo_type');
        Schema::dropIfExists('customer_combo_favorite');
        Schema::dropIfExists('headquarter_combo');
        Schema::dropIfExists('combos');
        Schema::dropIfExists('combo_types');
        Schema::dropIfExists('customer_ticket_office_awards');

        Schema::dropIfExists('ticket_office_awards');
        Schema::dropIfExists('customer_ticket_office_promos');
        if (Schema::hasTable('ticket_office_promos')) {
            Schema::rename('ticket_office_promos', 'ticket_promotions');
        }

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
