<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTradeNameToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('trade_name', 20)->after('type_user')->default('CINESTAR');
            $table->dropUnique('users_username_unique');
            $table->unique(['username', 'trade_name']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->string('trade_name', 20)->after('user_id')->default('CINESTAR');
            $table->dropUnique('customers_document_number_unique');
            $table->unique(['document_number', 'trade_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
