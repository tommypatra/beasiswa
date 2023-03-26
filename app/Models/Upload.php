<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    //
    protected $guarded = ["id"];



    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function syarat()
    {
        return $this->belongsTo(Syarat::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
