<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    //use HasFactory;
    protected $guarded = ["id"];

    public function upload()
    {
        return $this->hasMany(Upload::class);
    }

    public function admin()
    {
        return $this->hasMany(Admin::class);
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function fotoUser()
    {
        return $this->hasMany(FotoUser::class);
    }

    public function fileWeb()
    {
        return $this->hasMany(FileWeb::class);
    }
}
