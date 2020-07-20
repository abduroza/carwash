<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizeColumnToProductTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_type', function (Blueprint $table) {
            //menambahkan kolom baru yaitu size di table product_type
            //karena membuat kolom baru, maka harus diberi nullable() biar tidak ada error di data sebelumnya
            $table->string('size')->nullable()->after('type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_type', function (Blueprint $table) {
            //menghapus kolom size
            $table->dropColumn('size');
        });
    }
}
