<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteCodeColumnToTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types', function (Blueprint $table) {
            //karena ingin menghapus kolom, maka pada method up dilakukan penghapusan kolom
            $table->dropColumn('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('types', function (Blueprint $table) {
            //sedangkan pada method down, kita buat lagi kolom tsb supaya tidak ada error saat rollback
            $table->char('code')->comment('0: motor, 1: mobil, 2: truck')->nullable()->after('name');
        });
    }
}
