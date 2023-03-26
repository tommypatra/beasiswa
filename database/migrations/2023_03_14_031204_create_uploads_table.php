<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId("pendaftar_id");
            $table->foreignId("syarat_id");
            $table->timestamps();
            $table->foreign("pendaftar_id")->references("id")->on("pendaftars")->restrictOnDelete();
            $table->foreign("syarat_id")->references("id")->on("syarats")->restrictOnDelete();
            $table->foreignId("file_id")->nullable();
            $table->foreign("file_id")->references("id")->on("files")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
