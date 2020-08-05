<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // $table->dropForeign(['transaction_id']);
            $table->dropColumn('transaction_id');

            // $table->uuid('order_id')->nullable()->after('id');
            // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
            $table->uuid('transaction_id')->nullable()->after('id');

            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }
}
