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
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id')->nullable()->index()->foreign()->references('id')->on('purchases')->onDelete('cascade');
            $table->unsignedBigInteger('beparian_purchase_id')->nullable()->index()->foreign()->references('id')->on('beparian_purchases')->onDelete('cascade');
            $table->unsignedBigInteger('regular_purchase_id')->nullable()->index()->foreign()->references('id')->on('regular_purchases')->onDelete('cascade');
            $table->unsignedBigInteger('company_id')->index()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable()->index()->foreign()->references('id')->on('suppliers')->onDelete('cascade');
            $table->date('purchase_date')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('purchase_invoice')->nullable();
            $table->string('beparian_purchase_invoice')->nullable();
            $table->string('regular_purchase_invoice')->nullable();
            $table->decimal('total_amount',14,2)->default(0)->nullable();
            $table->decimal('total_payment',14,2)->default(0)->nullable();
            $table->decimal('total_due',14,2)->default(0)->nullable();
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
        Schema::dropIfExists('supplier_payments');
    }
};
