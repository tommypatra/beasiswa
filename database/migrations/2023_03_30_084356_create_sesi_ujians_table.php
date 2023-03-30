<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSesiUjiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sesi_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ruang_penguji_id");
            $table->foreignId("ujian_id");
            $table->foreignId("sesi_id");
            $table->timestamps();
            $table->foreign("ruang_penguji_id")->references("id")->on("ruang_pengujis")->restrictOnDelete();
            $table->foreign("ujian_id")->references("id")->on("ujians")->restrictOnDelete();
            $table->foreign("sesi_id")->references("id")->on("sesis")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sesi_ujians');
    }
}
