<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    //
    protected $guarded = ["id"];

    public function pj()
    {
        return $this->hasMany(Pj::class);
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function pesertaSesi()
    {
        return $this->belongsTo(PesertaSesi::class);
    }
}
