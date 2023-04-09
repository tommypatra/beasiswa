<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerifikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifikasis', function (Blueprint $table) {
            $table->id();
            $table->boolean("status")->default(false);
            $table->text("keterangan")->nullable();
            $table->foreignId("upload_id")->unique();
            $table->foreignId("pegawai_id");
            $table->timestamps();
            $table->foreign("upload_id")->references("id")->on("uploads")->restrictOnDelete();
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
        Schema::dropIfExists('verifikasis');
    }
}
