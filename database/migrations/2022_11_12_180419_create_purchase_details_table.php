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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('purchase_id')->nullable()->index()->foreign()->references('id')->on('purchases')->onDelete('cascade');
            $table->unsignedBigInteger('beparian_purchase_id')->nullable()->index()->foreign()->references('id')->on('beparian_purchases')->onDelete('cascade');
            $table->unsignedBigInteger('regular_purchase_id')->nullable()->index()->foreign()->references('id')->on('regular_purchases')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->index()->foreign()->references('id')->on('products')->onDelete('cascade');
            $table->string('lot_no')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('quantity_bag',14,2)->nullable()->default(0);
            $table->decimal('quantity_kg',14,2)->default(0);
            $table->decimal('discount',14,2)->default(0)->nullable();
            $table->decimal('less_quantity_kg',14,2)->nullable()->default(0);
            $table->decimal('actual_quantity',14,2)->default(0);
            $table->decimal('rate_kg',14,2)->default(0);
            $table->decimal('amount',14,2)->default(0);
            $table->decimal('purchase_commission',14,2)->nullable()->default(0);
            $table->decimal('transport_cost',14,2)->nullable()->default(0);
            $table->decimal('unloading_cost',14,2)->nullable()->default(0);
            $table->decimal('sale_income_per_bag',14,2)->nullable()->default(0);
            $table->decimal('total_amount',14,2)->nullable()->default(0);
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
};
