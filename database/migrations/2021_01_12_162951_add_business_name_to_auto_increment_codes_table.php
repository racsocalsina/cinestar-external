<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinessNameToAutoIncrementCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auto_increment_codes', function (Blueprint $table) {
            $table->dropUnique('auto_increment_codes_code_unique');
        });
        Schema::table('auto_increment_codes', function (Blueprint $table) {
            $table->string('business_name', 20)->after('code')->default('TOP_RANK');
        });
        Schema::table('auto_increment_codes', function (Blueprint $table) {
            $table->unique(['business_name', 'code'], 'serial_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_increment_codes', function (Blueprint $table) {
            $table->dropUnique('serial_number_unique');
        });
        Schema::table('auto_increment_codes', function (Blueprint $table) {
            $table->dropColumn('business_name', 20);
        });
        Schema::table('auto_increment_codes', function (Blueprint $table) {
            $table->unique('code');
        });
    }
}
