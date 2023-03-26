<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string("no_pegawai", 150)->unique();
            $table->foreignId("user_id");
            $table->foreignId("bagian_id");
            $table->enum("aktif", ["0", "1"])->default("0");
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->restrictOnDelete();
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
        Schema::dropIfExists('pegawais');
    }
}
