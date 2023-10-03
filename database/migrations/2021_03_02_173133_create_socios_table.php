<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cinestar_socios')->create('qmaecod', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo', 120);
            $table->string('serie', 13)->nullable();
            $table->integer('teatro')->default(0);
            $table->timestamp('fecha_creacion')->nullable()->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('fecha_modificacion')->nullable();
            $table->string('ip', 16)->nullable();
            $table->integer('estado')->nullable()->default(0);
            $table->integer('generado')->default(0);
            $table->string('promotion_code', 5);
        });

        Schema::connection('cinestar_socios')->create('points_history', function (Blueprint $table) {
            $table->string('soccod', 13);
            $table->char('sales_type', 1);
            $table->boolean('increase');
            $table->integer('points');
            $table->timestamp('created_at')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->date('expiration_date')->nullable();
            $table->boolean('from_erp')->default(1);
            $table->unsignedBigInteger('purchase_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qmaecod');
        Schema::dropIfExists('points_history');
    }
}
