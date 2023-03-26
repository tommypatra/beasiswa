<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianPeserta extends Model
{
    //
    protected $guarded = ["id"];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
