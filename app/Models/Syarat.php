<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syarat extends Model
{
    //
    protected $guarded = ["id"];

    public function upload()
    {
        return $this->hasMany(Upload::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }
}
