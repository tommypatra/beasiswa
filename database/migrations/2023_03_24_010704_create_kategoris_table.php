<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategorisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string("kategori", 250)->nullable();
            $table->enum("profil", ["0", "1"])->default("0");
            $table->enum("aktif", ["0", "1"])->default("0");
            $table->timestamps();
            $table->foreignId("user_id");
            $table->foreign("user_id")->references("id")->on("users")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategoris');
    }
}
