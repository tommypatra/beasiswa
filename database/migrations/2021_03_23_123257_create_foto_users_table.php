<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFotoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foto_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->restrictOnDelete();
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
        Schema::dropIfExists('foto_users');
    }
}
