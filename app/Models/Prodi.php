<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    //
    protected $guarded = ["id"];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
}
