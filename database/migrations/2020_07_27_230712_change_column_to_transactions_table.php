<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('total_price');

            // $table->uuid('product_id')->nullable()->after('order_id');
            // $table->uuid('type_id')->nullable()->after('product_id');
            $table->string('size')->nullable()->after('type_id');
            $table->integer('quantity')->nullable()->after('size');
            $table->integer('price')->nullable()->after('quantity');
            $table->integer('subtotal')->nullable()->after('price');

            

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
            // $table->integer('total_price')->nullable()->after('order_id');

            // $table->dropForeign(['product_id']);
            // $table->dropForeign(['type_id']);
            // $table->dropColumn('product_id');
            // $table->dropColumn('product_id');
            // $table->dropColumn('size');
            // $table->dropColumn('quantity');
            // $table->dropColumn('price');
            $table->dropColumn('subtotal');
        });
    }
}
