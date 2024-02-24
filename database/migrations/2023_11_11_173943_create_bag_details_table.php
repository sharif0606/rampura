<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bag_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->unsignedBigInteger('sales_details_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('bag_no')->nullable();
            $table->string('quantity_kg')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('bag_details');
    }
};
