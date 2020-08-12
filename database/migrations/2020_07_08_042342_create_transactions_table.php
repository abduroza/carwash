<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('product_id'); //baru
            $table->uuid('type_id'); //baru
            $table->string('size'); //baru
            $table->integer('quantity'); //baru
            $table->integer('price'); //baru
            $table->integer('subtotal'); //baru
            $table->char('status')->comment('0: process, 1: done');
            $table->datetime('checkin')->nullable();
            $table->datetime('checkout')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); //baru
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade'); //baru
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['product_id']); //baru
            $table->dropForeign(['type_id']); //baru
        });
        Schema::dropIfExists('transactions');
    }
}
