<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    //
    protected $guarded = ["id"];

    public function beasiswa()
    {
        return $this->hasMany(Beasiswa::class);
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    public function ruangPenguji()
    {
        return $this->belongsTo(RuangPenguji::class);
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
