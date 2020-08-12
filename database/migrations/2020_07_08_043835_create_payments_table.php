<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id'); //baru
            $table->integer('amount');
            $table->integer('customer_change')->default(0);
            $table->char('type')->default(0)->comment('0: Cash 1: Deposite, 2: Cash and Deposite');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); //baru            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']); //baru           
        });
        Schema::dropIfExists('payments');
    }
}
