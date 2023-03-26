<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    //
    protected $guarded = ["id"];

    public function beasiswa()
    {
        return $this->hasMany(Beasiswa::class);
    }
}
