<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users'); //to prevent error while deploy to heroku. Base table or view already exists: 1050 Table 'users' already exists 
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('photo')->nullable();
            // api_token nantinya akan berisi string random yang berfungsi sebagai token dari ajax request
            $table->string('api_token')->nullable();
            $table->char('role', 1)->comment('0: superadmin, 1: admin, 2: finance, 3: operator');
            $table->uuid('outlet_id')->nullable();
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
        Schema::dropIfExists('users');
    }
}
