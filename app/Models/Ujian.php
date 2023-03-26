<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    //
    protected $guarded = ["id"];

    public function sesi()
    {
        return $this->hasMany(Sesi::class);
    }

    public function ujianPeserta()
    {
        return $this->hasMany(UjianPeserta::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
