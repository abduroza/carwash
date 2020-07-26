<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeNotifiableIdColumnToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            //ubah tipe dari bigint menjadi uuid. sepertinya lebih baik buat field sendiri dari pada pakai morphs yg digenerate otomatis. pakai morphs tidak mendukung uuid. notifilable ini sifatnya unique
            $table->uuid('notifiable_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            //
        });
    }
}
