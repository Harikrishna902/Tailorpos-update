<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('online_orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('order_number');
            $table->string('customer_name')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->dateTime('preferred_delivery_time')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->integer('tax_type');
            $table->integer('status')->nullable();
            $table->double('sub_total',20,2);
            $table->double('tax_percentage',15,2)->nullable();
            $table->double('tax_amount',15,2)->nullable();
            $table->double('taxable_amount',20,2)->nullable();
            $table->double('total',20,2);
            $table->longText('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('financial_year_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_orders');
    }
};
