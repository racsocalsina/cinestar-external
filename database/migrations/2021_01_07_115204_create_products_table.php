<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ---------------------------------------------------------
        // Products
        // ---------------------------------------------------------
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 80);
            $table->string('image', 50);
            $table->timestamps();
        });

        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 80);
            $table->timestamps();
        });

        Schema::create('headquarter_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('headquarter_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_type_id');
            $table->boolean('active')->default(false);
            $table->decimal('stock', 12, 5);
            $table->boolean('igv')->nullable(false);
            $table->boolean('isc')->nullable(false);
            $table->timestamps();

            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_type_id')->references('id')->on('product_types');
        });


        Schema::create('headquarter_product_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('headquarter_id');
            $table->unsignedBigInteger('product_type_id');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('cascade');
            $table->foreign('product_type_id')->references('id')->on('product_types')->onDelete('cascade');
        });

        // ---------------------------------------------------------
        // Combos
        // ---------------------------------------------------------

        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 80);
            $table->string('image', 50);
            $table->timestamps();
        });

        Schema::create('combo_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 80);
            $table->timestamps();
        });

        Schema::create('headquarter_combo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('headquarter_id');
            $table->unsignedBigInteger('combo_id');
            $table->unsignedBigInteger('combo_type_id');
            $table->boolean('active')->default(false);
            $table->decimal('stock', 12, 5);
            $table->boolean('igv')->nullable(false);
            $table->boolean('isc')->nullable(false);
            $table->timestamps();

            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('cascade');
            $table->foreign('combo_id')->references('id')->on('combos')->onDelete('cascade');
            $table->foreign('combo_type_id')->references('id')->on('combo_types');
        });


        Schema::create('headquarter_combo_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('headquarter_id');
            $table->unsignedBigInteger('combo_type_id');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('headquarter_id')->references('id')->on('headquarters')->onDelete('cascade');
            $table->foreign('combo_type_id')->references('id')->on('combo_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('headquarter_product_type');
        Schema::dropIfExists('headquarter_product');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('products');

        Schema::dropIfExists('headquarter_combo_type');
        Schema::dropIfExists('headquarter_combo');
        Schema::dropIfExists('combo_types');
        Schema::dropIfExists('combos');
    }
}
