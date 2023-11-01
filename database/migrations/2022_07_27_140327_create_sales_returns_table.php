<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('sales_return_number')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_file_number')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->integer('tax_type')->nullable();
            $table->double('sub_total',20,2)->nullable();
            $table->double('tax_percentage',15,2)->nullable();
            $table->double('tax_amount',15,2)->nullable();
            $table->double('taxable_amount',20,2)->nullable();
            $table->double('total',20,2);
            $table->integer('total_quantity')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('financial_year_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
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
        Schema::dropIfExists('sales_returns');
    }
}
