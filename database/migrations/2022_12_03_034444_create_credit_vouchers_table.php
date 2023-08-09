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
        Schema::create('credit_vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('voucher_no');
            $table->string('current_date');
            $table->string('pay_name')->nullable();
            $table->string('purpose')->nullable();
            $table->decimal('debit_sum',10,2)->default(0);
            $table->decimal('credit_sum',10,2)->default(0);
            $table->string('cheque_no')->nullable();
            $table->string('cheque_dt')->nullable();
            $table->string('bank')->nullable();
            $table->string('slip')->nullable();

             // default
             $table->unsignedBigInteger('created_by')->index()->default(2);
             $table->unsignedBigInteger('updated_by')->index()->nullable();
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
        Schema::dropIfExists('credit_vouchers');
    }
};
