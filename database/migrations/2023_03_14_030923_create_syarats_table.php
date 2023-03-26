<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyaratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syarats', function (Blueprint $table) {
            $table->id();
            $table->string("nama", 150)->nullable();
            $table->string("keterangan")->nullable();
            $table->foreignId("beasiswa_id");
            $table->enum("wajib", ["0", "1"])->default("0");
            $table->enum("aktif", ["0", "1"])->default("0");
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
        Schema::dropIfExists('syarats');
    }
}
