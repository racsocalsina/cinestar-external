<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsAndContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('lastname', 100);
            $table->string('email', 50);
            $table->string('document_number', 30);
            $table->string('district_name', 30);
            $table->string('address', 200);
            $table->date('birth_date');
            $table->string('education_level', 30);
            $table->string('file_guid')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamps();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('lastname', 100);
            $table->string('email', 50);
            $table->string('district_name', 30);
            $table->longText('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_applications_and_contacts');
    }
}
