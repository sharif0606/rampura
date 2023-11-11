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
        Schema::create('regular_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->index()->foreign()->references('id')->on('suppliers')->onDelete('cascade');
            $table->string('voucher_no')->nullable();
            $table->date('purchase_date');
            $table->string('reference_no')->nullable();
            $table->decimal('grand_total',10,2)->default(0)->nullable();
            $table->unsignedBigInteger('company_id')->index()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->index()->foreign()->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_id')->index()->foreign()->references('id')->on('warehouses')->onDelete('cascade');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->integer('payment_status')->comment('0 unpaid, 1 paid, 2 partial_paid')->default(0)->nullable();
            $table->integer('status')->comment('1 parches, 2 return, 3 partial_return, 4 cancel')->default(1);
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
        Schema::dropIfExists('regular_purchases');
    }
};
