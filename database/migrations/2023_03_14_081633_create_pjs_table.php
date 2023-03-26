<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pjs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sesi_id");
            $table->foreignId("pegawai_id");
            $table->timestamps();
            $table->foreign("sesi_id")->references("id")->on("sesis")->restrictOnDelete();
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
        Schema::dropIfExists('pjs');
    }
}
