<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuangPenguji extends Model
{
    protected $guarded = ["id"];

    public function sesiUjian()
    {
        return $this->hasMany(SesiUjian::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function ruangBeasiswa()
    {
        return $this->belongsTo(RuangBeasiswa::class);
    }
}
