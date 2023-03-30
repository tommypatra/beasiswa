<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuangPendaftarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruang_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ruang_beasiswa_id");
            $table->foreignId("pendaftar_id");
            $table->float("nilai", 4, 2)->nullable();
            $table->timestamps();
            $table->foreign("ruang_beasiswa_id")->references("id")->on("ruang_beasiswas")->restrictOnDelete();
            $table->foreign("pendaftar_id")->references("id")->on("pendaftars")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ruang_pendaftars');
    }
}
