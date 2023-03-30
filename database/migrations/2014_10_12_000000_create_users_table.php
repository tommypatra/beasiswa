<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150)->nullable();
            $table->string('glrdepan', 30)->nullable();
            $table->string('glrbelakang', 30)->nullable();
            $table->string('nohp', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('email', 150)->unique();
            $table->string('password')->nullable();
            $table->string('tempatlahir', 50)->nullable();
            $table->text('tentang')->nullable();
            $table->string('fb')->nullable();
            $table->string('ig')->nullable();
            $table->string('twitter')->nullable();
            $table->date('tanggallahir')->nullable();
            $table->enum("kel", ["L", "P"])->default("L");
            $table->boolean("aktif")->default(false);

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
        Schema::dropIfExists('users');
    }
}
