<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('head_name');
            $table->string('head_code');
            $table->unique(['company_id', 'head_code']);
            $table->string('opening_balance');
            $table->date('opening_balance_date');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('master_accounts')->insert([
            [
                'company_id' => '1',
                'head_name' => 'Assets',
                'head_code' => '1000',
                'opening_balance' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],[
                'company_id' => '1',
                'head_name' => 'Liabilities',
                'head_code' => '2000',
                'opening_balance' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'company_id' => '1',
                'head_name' => 'Capital',
                'head_code' => '3000',
                'opening_balance' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'company_id' => '1',
                'head_name' => 'Income',
                'head_code' => '4000',
                'opening_balance' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'company_id' => '1',
                'head_name' => 'Expense',
                'head_code' => '5000',
                'opening_balance' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_accounts');
    }
};
