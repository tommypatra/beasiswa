<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string("nim", 150)->unique();
            $table->string("idprodi", 150)->nullable();
            $table->string("prodi", 150)->nullable();
            $table->string("fakultas", 150)->nullable();
            $table->foreignId("user_id");
            $table->foreignId("prodi_id")->nullable();
            $table->foreignId("bagian_id");
            $table->enum("aktif", ["0", "1"])->default("0");
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->restrictOnDelete();
            $table->foreign("prodi_id")->references("id")->on("prodis")->restrictOnDelete();
            $table->foreign("bagian_id")->references("id")->on("bagians")->restrictOnDelete();
            // $table->foreignId("file_id")->nullable();
            // $table->foreign("file_id")->references("id")->on("files")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahasiswas');
    }
}
