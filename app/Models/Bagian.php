<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    //
    protected $guarded = ["id"];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function admin()
    {
        return $this->hasMany(Admin::class);
    }
}
