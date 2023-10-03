<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_errors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('headquarter_id');
            $table->string('headquarter_name');

            $table->string('db_name');
            $table->unsignedBigInteger('job_trigger_id');
            $table->string('actionable', 50);
            $table->string('actionable_id', 150);
            $table->string('action_realized', 20);

            $table->string('code', 10)->nullable();
            $table->integer('line')->nullable();
            $table->text('file')->nullable();
            $table->text('message');
            $table->longText('trace')->nullable();

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
        Schema::dropIfExists('internal_errors');
    }
}
