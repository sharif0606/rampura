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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id')->nullable()->index()->foreign()->references('id')->on('sales')->onDelete('cascade');
            $table->integer('sales_return_id')->nullable();
            $table->unsignedBigInteger('company_id')->index()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id')->index()->foreign()->references('id')->on('customers')->onDelete('cascade');
            $table->date('sales_date')->nullable();
            $table->string('sales_invoice')->nullable();
            $table->string('invoice_id')->nullable();
            $table->decimal('total_amount',14,2)->default(0)->nullable();
            $table->decimal('total_payment',14,2)->default(0)->nullable();
            $table->decimal('total_due',14,2)->default(0)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_payments');
    }
};
