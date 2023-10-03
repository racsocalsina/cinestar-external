<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiUrlToHeadquartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headquarters', function (Blueprint $table) {
            $table->string('api_url')->after('point_sale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('headquarters', 'api_url')) {
            Schema::table('headquarters', function (Blueprint $table) {
                $table->dropColumn('api_url');
            });
        }
    }
}
