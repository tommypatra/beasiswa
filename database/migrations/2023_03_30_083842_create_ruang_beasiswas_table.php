<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuangBeasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruang_beasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ruang_id");
            $table->foreignId("beasiswa_id");
            $table->timestamps();
            $table->foreign("ruang_id")->references("id")->on("ruangs")->restrictOnDelete();
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
        Schema::dropIfExists('ruang_beasiswas');
    }
}
