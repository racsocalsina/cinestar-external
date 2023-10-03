<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTradeNameSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('trade_name', 20)->after('email')->default('CINESTAR');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->string('trade_name', 20)->after('message')->default('CINESTAR');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('trade_name', 20)->after('file_name')->default('CINESTAR');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
