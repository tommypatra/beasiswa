<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $guarded = ["id"];


    public function upload()
    {
        return $this->hasMany(Upload::class);
    }

    public function fileBerita()
    {
        return $this->hasMany(Berita::class);
    }

    public function fotoUser()
    {
        return $this->hasMany(FotoUser::class);
    }

    public function admin()
    {
        return $this->hasMany(Admin::class);
    }

    public function fileWeb()
    {
        return $this->hasMany(FileWeb::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
