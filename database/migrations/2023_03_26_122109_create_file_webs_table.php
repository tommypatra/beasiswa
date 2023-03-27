<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileWebsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_webs', function (Blueprint $table) {
            $table->id();
            $table->string("judul")->nullable();
            $table->string("slug")->nullable();
            $table->text("deskripsi")->nullable();
            $table->date("tgl")->nullable();
            $table->foreignId("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->restrictOnDelete();
            $table->foreignId("file_id")->unique();
            $table->foreign("file_id")->references("id")->on("files")->restrictOnDelete();
            $table->timestamps();
            $table->enum("aktif", ["0", "1"])->default("0");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_webs');
    }
}
