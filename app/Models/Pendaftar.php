<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    //
    protected $guarded = ["id"];

    public function ruangPendaftar()
    {
        return $this->hasMany(RuangPendaftar::class);
    }

    public function upload()
    {
        return $this->hasMany(Upload::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
