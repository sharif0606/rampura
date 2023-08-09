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
        Schema::create('general_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('journal_title')->nullable();
            $table->string('dr')->default(0);
            $table->string('cr')->default(0);
            $table->string('rec_date');
            $table->string('jv_id');
            $table->string('master_account_id')->nullable();
            $table->string('sub_head_id')->nullable();
            $table->string('child_one_id')->nullable();
            $table->string('child_two_id')->nullable();
            $table->string('debit_voucher_id')->nullable();
            $table->string('devoucher_bkdn_id')->nullable();
            $table->string('credit_voucher_id')->nullable();
            $table->string('crvoucher_bkdn_id')->nullable();
            $table->string('journal_voucher_id')->nullable();
            $table->string('journal_voucher_bkdn_id')->nullable();

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
        Schema::dropIfExists('general_ledgers');
    }
};
