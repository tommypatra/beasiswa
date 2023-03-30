<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuangPengujisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruang_pengujis', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ruang_beasiswa_id");
            $table->foreignId("pegawai_id");
            $table->timestamps();
            $table->foreign("ruang_beasiswa_id")->references("id")->on("ruang_beasiswas")->restrictOnDelete();
            $table->foreign("pegawai_id")->references("id")->on("pegawais")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ruang_pengujis');
    }
}
