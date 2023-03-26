<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUjianPesertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ujian_pesertas', function (Blueprint $table) {
            $table->id();
            $table->float("nilai", 4, 2)->nullable();
            $table->date("tgl")->nullable();
            $table->foreignId("peserta_id");
            $table->foreignId("ujian_id");
            $table->timestamps();
            $table->foreign("peserta_id")->references("id")->on("pesertas")->restrictOnDelete();
            $table->foreign("ujian_id")->references("id")->on("ujians")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ujian_pesertas');
    }
}
