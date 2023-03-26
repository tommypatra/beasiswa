<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSesisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sesis', function (Blueprint $table) {
            $table->id();
            $table->time("jam_mulai")->nullable();
            $table->time("jam_selesai")->nullable();
            $table->date("tgl")->nullable();
            $table->foreignId("ujian_id");
            $table->foreignId("ruang_id");
            $table->timestamps();
            $table->foreign("ujian_id")->references("id")->on("ujians")->restrictOnDelete();
            $table->foreign("ruang_id")->references("id")->on("ruangs")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sesis');
    }
}
