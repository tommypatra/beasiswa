<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileBeritasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_beritas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("berita_id")->nullable();
            $table->foreign("berita_id")->references("id")->on("beritas")->restrictOnDelete();
            $table->foreignId("file_id")->nullable();
            $table->foreign("file_id")->references("id")->on("files")->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_beritas');
    }
}
