<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createUbigeo();

        Schema::create('document_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
        });

        Schema::create('claim_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
            $table->string('description', 100);
        });

        Schema::create('claim_identification_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50);
        });

        Schema::create('claims', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20);
            $table->char('sede_district_id', 6);
            $table->smallInteger('claim_type_id')->unsigned();
            $table->smallInteger('identification_type_id')->unsigned();
            $table->longText('detail')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('name', 100);
            $table->string('lastname', 100);
            $table->boolean('older');
            $table->smallInteger('document_type_id')->unsigned();
            $table->string('document_number', 30);
            $table->string('representative_name', 100)->nullable();
            $table->string('address', 200);
            $table->char('person_district_id', 6);
            $table->string('cellphone', 20);
            $table->string('email', 50);

            $table->timestamps();

            $table->foreign('claim_type_id')->references('id')->on('claim_types');
            $table->foreign('identification_type_id')->references('id')->on('claim_identification_types');
            $table->foreign('document_type_id')->references('id')->on('document_types');
        });
    }

    private function createUbigeo()
    {
        $path = app_path('../database/scripts/ubigeo.sql');
        $contents = file_get_contents($path);
        DB::connection()->unprepared($contents);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claims');
        Schema::dropIfExists('claim_identification_types');
        Schema::dropIfExists('claim_types');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('ubdistricts');
        Schema::dropIfExists('ubprovinces');
        Schema::dropIfExists('ubdepartments');
    }
}
