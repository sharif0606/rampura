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
        Schema::create('lc_numbers', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('lot_name')->nullable();
            $table->string('lot_no')->nullable();
            $table->integer('billable')->default(0)->comment('1 billable,0 non billable');
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
        Schema::dropIfExists('lc_numbers');
    }
};
