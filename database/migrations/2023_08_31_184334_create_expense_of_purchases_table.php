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
        Schema::create('expense_of_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index()->foreign()->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('purchase_id')->nullable()->index()->foreign()->references('id')->on('purchases')->onDelete('cascade');
            $table->unsignedBigInteger('beparian_purchase_id')->nullable()->index()->foreign()->references('id')->on('beparian_purchases')->onDelete('cascade');
            $table->unsignedBigInteger('regular_purchase_id')->nullable()->index()->foreign()->references('id')->on('regular_purchases')->onDelete('cascade');
            $table->bigInteger('child_two_id')->nullable();
            $table->decimal('cost_amount',14,2)->nullable();
            $table->string('lot_no')->nullable();
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
        Schema::dropIfExists('expense_of_purchases');
    }
};
