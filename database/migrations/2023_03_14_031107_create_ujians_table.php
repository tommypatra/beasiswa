<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUjiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->string("ujian", 150)->nullable();
            $table->date("tgl_mulai")->nullable();
            $table->date("tgl_selesai")->nullable();
            $table->foreignId("beasiswa_id");
            $table->enum("aktif", ["0", "1"])->default("0");
            $table->timestamps();
            $table->foreign("beasiswa_id")->references("id")->on("beasiswas")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ujians');
    }
}
