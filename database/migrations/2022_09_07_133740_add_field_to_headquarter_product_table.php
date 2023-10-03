<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToHeadquarterProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headquarter_product', function (Blueprint $table) {
            $table->boolean('is_presale')->default(false);
            $table->timestamp('presale_start')->nullable()->default(null);
            $table->timestamp('presale_end')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headquarter_product', function (Blueprint $table) {
            //
        });
    }
}
