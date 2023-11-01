<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->string('material_name')->nullable();
            $table->integer('material_unit')->nullable();
            $table->double('purchase_quantity',15,2)->nullable();
            $table->double('purchase_price',15,2)->nullable();
            $table->double('tax_amount',15,2)->nullable();
            $table->double('purchase_item_total',15,2)->nullable();
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
        Schema::dropIfExists('purchase_details');
    }
}
