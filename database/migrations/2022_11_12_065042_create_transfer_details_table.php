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
        Schema::create('transfer_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_id')->index();
            $table->foreign('transfer_id')->references('id')->on('transfers')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->decimal('quantity',10,2,)->default(0);
            $table->decimal('unit_price',10,2,)->default(0);
            $table->decimal('sub_amount',10,2,)->default(0);
            $table->decimal('tax',10,2,)->default(0);
            $table->decimal('discount',10,2,)->default(0);
            $table->decimal('total_amount',10,2,)->default(0);
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
        Schema::dropIfExists('transfer_details');
    }
};
