<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    //
    protected $guarded = ["id"];

    public function ujianPeserta()
    {
        return $this->hasMany(UjianPeserta::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
