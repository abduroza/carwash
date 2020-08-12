<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            // $table->morphs('notifiable');
            //dari pada pakai morphs yg mana akan menggenerate 2 field: notifiable_type dan notifiable_id, lebih baik buat sendiri aja, biar type data bisa diatur
            $table->string('notifiable_type'); //baru
            $table->uuid('notifiable_id'); //baru
            $table->text('data');
            $table->timestamp('read_at')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
