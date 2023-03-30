<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    //use HasFactory;
    protected $guarded = ["id"];

    public function file()
    {
        return $this->hasMany(File::class);
    }

    public function fileWeb()
    {
        return $this->hasMany(FileWeb::class);
    }

    public function admin()
    {
        return $this->hasMany(Admin::class);
    }

    public function fotoUser()
    {
        return $this->hasMany(FotoUser::class);
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function kategori()
    {
        return $this->hasMany(Kategori::class);
    }

    public function berita()
    {
        return $this->hasMany(Berita::class);
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }
}
