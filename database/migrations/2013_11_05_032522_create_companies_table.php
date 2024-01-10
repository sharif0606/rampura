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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('lc_expense')->nullable();
            $table->string('income_head')->nullable();
            $table->string('company_bn')->nullable();
            $table->string('contact_bn')->nullable();
            $table->string('address_bn')->nullable();
            $table->string('binNumber',1000)->nullable();
            $table->string('tradeNumber',1000)->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('division_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('upazila_id')->nullable();
            $table->bigInteger('thana_id')->nullable();
            $table->string('address',1000)->nullable();
            $table->string('currency')->nullable();
            $table->boolean('status')->default(1)->comment('1=>active 2=>inactive');
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
        Schema::dropIfExists('companies');
    }
};
