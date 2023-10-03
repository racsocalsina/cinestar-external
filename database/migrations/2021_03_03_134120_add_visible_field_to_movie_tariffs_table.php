<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibleFieldToMovieTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movie_tariffs', function (Blueprint $table) {
            $table->boolean('visible')->default(1)->after('name');
        });

        $data = \App\Models\MovieTariff\MovieTariff::where('remote_funtar', 'Z')->first();
        if($data)
        {
            $data->visible = false;
            $data->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movie_tariffs', function (Blueprint $table) {
            $table->dropColumn('visible');
        });
    }
}
