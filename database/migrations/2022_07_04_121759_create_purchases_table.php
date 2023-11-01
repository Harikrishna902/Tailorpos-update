<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('purchase_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->double('sub_total',15,2)->nullable();
            $table->double('discount',15,2)->nullable();
            $table->double('tax_percentage',15,2)->nullable();
            $table->double('tax_amount',15,2)->nullable();
            $table->double('total_quantity')->nullable();
            $table->double('service_charge',15,2)->nullable();
            $table->double('total',15,2)->nullable();
            $table->unsignedBigInteger('financial_year_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->integer('purchase_type')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
