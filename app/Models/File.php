<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $guarded = ["id"];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function fotoUser() //ok
    {
        return $this->hasMany(FotoUser::class);
    }

    public function upload() //ok
    {
        return $this->hasMany(Upload::class);
    }

    public function admin() //ok
    {
        return $this->hasMany(Admin::class);
    }

    // public function mahasiswa()
    // {
    //     return $this->hasMany(Mahasiswa::class);
    // }

    // public function pegawai()
    // {
    //     return $this->hasMany(Pegawai::class);
    // }

    public function fileBerita() //ok
    {
        return $this->hasMany(Berita::class);
    }

    public function fileWeb()
    {
        return $this->hasMany(FileWeb::class);
    }
}
