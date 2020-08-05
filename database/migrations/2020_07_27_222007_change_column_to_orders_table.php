<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            //hapus column
            // $table->dropForeign(['product_id']);
            // $table->dropForeign(['type_id']);
            // $table->dropColumn('product_id');
            // $table->dropColumn('type_id');
            $table->dropColumn('quantity');
            $table->dropColumn('price');

            // $table->integer('amount')->nullable()->after('customer_id');
            // $table->boolean('isPaid')->default('0')->nullable()->after('amount');
            // $table->uuid('user_id')->nullable()->after('isPaid');

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //buat lagi column yg dihapus. di set nullable biar gak error
            $table->integer('quantity')->nullable()->after('customer_id');
            $table->integer('price')->nullable()->after('quantity');

            $table->dropColumn('amount');
            $table->dropColumn('isPaid');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
