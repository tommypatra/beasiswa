<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    //
    protected $guarded = ["id"];

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class);
    }

    public function pjs()
    {
        return $this->hasMany(Pjs::class);
    }

    public function beasiswa()
    {
        return $this->hasMany(Beasiswa::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function fileUpload()
    // {
    //     return $this->belongsTo(FileUpload::class);
    // }
}
