<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    //
    protected $guarded = ["id"];

    public function sesi()
    {
        return $this->hasMany(Sesi::class);
    }
}
