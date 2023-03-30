<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beasiswas', function (Blueprint $table) {
            $table->id();
            $table->string("nama", 150)->nullable();
            $table->year("tahun")->nullable();
            $table->date("daftar_mulai")->nullable();
            $table->date("daftar_selesai")->nullable();
            $table->date("verifikasi_mulai")->nullable();
            $table->date("verifikasi_selesai")->nullable();
            $table->smallInteger("sesi")->nullable();
            $table->smallInteger("peserta_ruang")->nullable();
            $table->smallInteger("ruang_sesi")->nullable();
            $table->foreignId("jenis_id");
            $table->foreignId("pegawai_id");
            $table->boolean("aktif")->default(false);
            $table->timestamps();
            $table->foreign("jenis_id")->references("id")->on("jenis")->restrictOnDelete();
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
        Schema::dropIfExists('beasiswas');
    }
}
