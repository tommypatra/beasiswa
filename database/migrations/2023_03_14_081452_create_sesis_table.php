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
            $table->string("sesi")->nullable();
            $table->time("jam_mulai")->nullable();
            $table->time("jam_selesai")->nullable();
            $table->date("tgl")->nullable();
            $table->foreignId("beasiswa_id");
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
        Schema::dropIfExists('sesis');
    }
}
