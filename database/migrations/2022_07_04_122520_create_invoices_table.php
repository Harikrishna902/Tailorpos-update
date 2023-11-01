<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('invoice_number');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_file_number')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('salesman_id')->nullable();
            $table->integer('tax_type');
            $table->double('discount',20,2);
            $table->double('sub_total',20,2);
            $table->double('tax_percentage',15,2)->nullable();
            $table->double('tax_amount',15,2)->nullable();
            $table->double('taxable_amount',20,2)->nullable();
            $table->double('total',20,2);
            $table->longText('notes')->nullable();
            $table->integer('total_quantity')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
