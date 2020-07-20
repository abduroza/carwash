<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteSizeColumnToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            //karena ingin menghapus kolom, maka pada method up dilakukan penghapusan kolom
            $table->dropColumn('size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //sedangkan pada method down, kita buat lagi kolom tsb supaya tidak ada error saat rollback
            $table->char('size')->comment('0: Small, 1: Medium, 2: Large')->nullable()->after('name');
        });
    }
}
