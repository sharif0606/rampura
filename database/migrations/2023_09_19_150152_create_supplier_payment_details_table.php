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
        Schema::create('supplier_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable()->index()->foreign()->references('id')->on('suppliers')->onDelete('cascade');
            $table->integer('supplier_payment_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable()->index()->foreign()->references('id')->on('purchases')->onDelete('cascade');
            $table->unsignedBigInteger('beparian_purchase_id')->nullable()->index()->foreign()->references('id')->on('beparian_purchases')->onDelete('cascade');
            $table->unsignedBigInteger('regular_purchase_id')->nullable()->index()->foreign()->references('id')->on('regular_purchases')->onDelete('cascade');
            $table->string('ptable_name')->nullable();
            $table->unsignedBigInteger('ptable_id')->nullable();
            $table->string('phead_name')->nullable();
            $table->string('phead_code')->nullable();
            $table->string('lc_no')->nullable();
            $table->decimal('amount',14,2)->default(0)->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('supplier_payment_details');
    }
};
