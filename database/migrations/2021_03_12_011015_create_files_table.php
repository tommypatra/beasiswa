<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string("path", 150)->nullable();
            $table->text("detail")->nullable();
            $table->enum("is_image", ["0", "1"])->default("0");
            $table->enum("is_file", ["0", "1"])->default("0");
            $table->foreignId("user_id");
            $table->timestamps();
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
        Schema::dropIfExists('file_uploads');
    }
}
