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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index()->nullable()->foreign()->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('purchase_id')->index()->nullable()->nullable()->foreign()->references('id')->on('purchases')->onDelete('cascade');
            $table->unsignedBigInteger('purchase_return_id')->index()->nullable()->nullable()->foreign()->references('id')->on('purchase_returns')->onDelete('cascade');
            $table->unsignedBigInteger('beparian_purchase_id')->nullable()->index()->nullable()->foreign()->references('id')->on('beparian_purchases')->onDelete('cascade');
            $table->unsignedBigInteger('regular_purchase_id')->nullable()->index()->nullable()->foreign()->references('id')->on('regular_purchases')->onDelete('cascade');
            $table->unsignedBigInteger('sales_id')->index()->nullable()->foreign()->references('id')->on('sales')->onDelete('cascade');
            $table->unsignedBigInteger('transfer_id')->index()->nullable()->foreign()->references('id')->on('transfers')->onDelete('cascade');
            $table->unsignedBigInteger('company_id')->index()->nullable()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->index()->nullable()->foreign()->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_id')->index()->nullable()->foreign()->references('id')->on('warehouses')->onDelete('cascade');
            $table->string('batch_id')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('quantity',10,2)->nullable()->default(0);
            $table->decimal('quantity_bag',10,2)->nullable()->default(0);
            $table->decimal('discount',10,2)->default(0)->nullable();
            $table->decimal('unit_price',10,2)->nullable()->default(0);
            $table->decimal('total_amount',10,2)->nullable()->default(0);
            $table->date('stock_date');
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
        Schema::dropIfExists('stocks');
    }
};
