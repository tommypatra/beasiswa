<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiUjian extends Model
{
    protected $guarded = ["id"];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function ruangPenguji()
    {
        return $this->belongsTo(RuangPenguji::class);
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }
}
