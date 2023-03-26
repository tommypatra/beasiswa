<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->foreignId("beasiswa_id");
            $table->foreignId("mahasiswa_id");
            $table->timestamps();
            $table->foreign("beasiswa_id")->references("id")->on("beasiswas")->restrictOnDelete();
            $table->foreign("mahasiswa_id")->references("id")->on("mahasiswas")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendaftars');
    }
}
