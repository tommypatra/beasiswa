<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    //
    protected $guarded = ["id"];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
