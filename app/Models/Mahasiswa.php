<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    //
    protected $guarded = ["id"];

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
