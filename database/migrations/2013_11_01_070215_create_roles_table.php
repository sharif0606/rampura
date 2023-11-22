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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('type',20)->unique();
            $table->string('identity',30)->unique();
            $table->timestamps();
        });
        DB::table('roles')->insert([
            [
                'type' => 'Admin',
                'identity' => 'admin',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Owner',
                'identity' => 'owner',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Manager',
                'identity' => 'manager',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Accounts Officer',
                'identity' => 'accountsofficer',
                'created_at' => Carbon::now()
            ],
            [
                'type' => 'Executive Officer',
                'identity' => 'executiveofficer',
                'created_at' => Carbon::now()
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
        Schema::dropIfExists('roles');
    }
};
