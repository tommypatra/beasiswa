<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    //
    protected $guarded = ["id"];

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }

    public function syarat()
    {
        return $this->hasMany(Syarat::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }
}
