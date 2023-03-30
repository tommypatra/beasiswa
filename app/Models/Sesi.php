<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    //
    protected $guarded = ["id"];

    public function sesiUjian()
    {
        return $this->hasMany(SesiUjian::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
