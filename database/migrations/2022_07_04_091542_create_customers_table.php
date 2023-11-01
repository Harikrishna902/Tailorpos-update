<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('email')->nullable();
            $table->string('file_number')->nullable();
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('phone_number_1');
            $table->string('phone_number_2')->nullable();
            $table->longText('address')->nullable();
            $table->longText('notes')->nullable();
            $table->unsignedBigInteger('customer_group_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->integer('is_active')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->double('opening_balance',20,2)->default(0);
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
        Schema::dropIfExists('customers');
    }
}
