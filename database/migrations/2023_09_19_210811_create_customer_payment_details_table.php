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
        Schema::create('customer_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id')->index()->foreign()->references('id')->on('customers')->onDelete('cascade');
            $table->integer('customer_payment_id')->nullable();
            $table->unsignedBigInteger('sales_id')->index()->foreign()->references('id')->on('sales')->onDelete('cascade');
            $table->string('p_table_name')->nullable();
            $table->unsignedBigInteger('p_table_id')->nullable();
            $table->string('p_head_name')->nullable();
            $table->string('p_head_code')->nullable();
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
        Schema::dropIfExists('customer_payment_details');
    }
};
