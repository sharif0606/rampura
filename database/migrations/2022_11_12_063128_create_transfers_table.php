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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->index();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_form')->index();
            $table->foreign('warehouse_form')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_to')->index();
            $table->foreign('warehouse_to')->references('id')->on('warehouses')->onDelete('cascade');
            $table->date('transfer_date');
            $table->decimal('quantity',10,2,)->default(0);
            $table->string('created_by');
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
        Schema::dropIfExists('transfers');
    }
};
