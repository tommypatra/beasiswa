<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    //
    protected $guarded = ["id"];

    public function ruangBeassiwa()
    {
        return $this->hasMany(RuangBeassiwa::class);
    }
}
