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
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index()->foreign()->references('id')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->string('voucher_no')->nullable();
            $table->integer('voucher_type')->default(0)->comment('0 regular voucher,1 cash voucher');
            $table->string('voucher_note')->nullable();
            $table->date('return_date')->nullable();
            $table->string('reference_no')->nullable();
            $table->decimal('grand_total',10,2)->default(0);
            $table->unsignedBigInteger('company_id')->index()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->index()->foreign()->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_id')->index()->foreign()->references('id')->on('warehouses')->onDelete('cascade');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->integer('payment_status')->comment('0 unpaid, 1 paid, 2 partial_paid')->default(0)->nullable();
            $table->integer('status')->comment('1 sale, 2 return, 3 partial_return, 4 cancel')->default(1);
            $table->string('status_note')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_returns');
    }
};
