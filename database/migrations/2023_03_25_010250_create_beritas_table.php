<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string("judul", 250)->nullable();
            $table->string("slug")->unique();
            $table->date("tgl")->nullable();
            $table->text("konten")->nullable();
            $table->enum("aktif", ["0", "1"])->default("0");
            $table->timestamps();
            $table->foreignId("user_id");
            $table->foreign("user_id")->references("id")->on("users")->restrictOnDelete();
            $table->foreignId("kategori_id");
            $table->foreign("kategori_id")->references("id")->on("kategoris")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beritas');
    }
}
