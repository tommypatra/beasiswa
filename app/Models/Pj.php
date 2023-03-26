<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pj extends Model
{
    //
    protected $guarded = ["id"];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }
}
