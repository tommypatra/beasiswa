<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuangBeasiswa extends Model
{
    //
    protected $guarded = ["id"];

    public function ruangPendaftar()
    {
        return $this->hasMany(RuangPendaftar::class);
    }

    public function ruangPenguji()
    {
        return $this->hasMany(RuangPenguji::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }
}
