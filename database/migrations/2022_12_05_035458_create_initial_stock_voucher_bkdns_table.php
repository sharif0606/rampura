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
        Schema::create('initial_stock_voucher_bkdns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('initial_stock_voucher_id');
            $table->unsignedBigInteger('supplier_id')->nullable()->index()->foreign()->references('id')->on('suppliers')->onDelete('cascade');
            $table->string('lc_no')->nullable();
            $table->string('particulars')->nullable();
            $table->string('account_code');
            $table->string('table_name');
            $table->string('table_id');
            $table->decimal('debit',10,2)->default(0);
            $table->decimal('credit',10,2)->default(0);

             // default
             $table->unsignedBigInteger('created_by')->index()->default(2);
             $table->unsignedBigInteger('updated_by')->index()->nullable();
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
        Schema::dropIfExists('initial_stock_voucher_bkdns');
    }
};
