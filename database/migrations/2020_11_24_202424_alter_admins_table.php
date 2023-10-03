<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAdminsTable extends Migration
{
    public function up()
    {
        DB::statement("delete from admins");

        Schema::table('admins', function (Blueprint $table) {
            $table->unsignedSmallInteger('document_type_id')->after('email');
            $table->string('document_number')->unique()->after('document_type_id');
            $table->unsignedBigInteger('headquarter_id')->nullable()->after('document_number');
            $table->date('entry_date')->after('headquarter_id');


            $table->foreign('headquarter_id')
                ->references('id')
                ->on('headquarters');

            $table->foreign('document_type_id')
                ->references('id')
                ->on('document_types');

            $table->dropColumn('username');
        });

    }

    public function down()
    {

    }
}
