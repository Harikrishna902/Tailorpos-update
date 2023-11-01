<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_return_id');
            $table->integer('type')->nullable();
            $table->double('tax_amount',20,2)->nullable();
            $table->double('quantity',15,2);
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('invoice_detail_id');
            $table->string('item_name');
            $table->double('rate',20,2);
            $table->double('total',20,2);
            $table->integer('unit_type')->nullable();
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
        Schema::dropIfExists('sales_return_details');
    }
}
