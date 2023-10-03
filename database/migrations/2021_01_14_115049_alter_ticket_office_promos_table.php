<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTicketOfficePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('headquarter_id')->nullable()->change();
            $table->string('product_code')->nullable()->change();
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
        Schema::table('ticket_promotions', function (Blueprint $table) {
            //
        });
    }
}
