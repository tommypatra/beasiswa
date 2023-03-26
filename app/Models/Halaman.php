<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Halaman extends Model
{
    protected $guarded = ["id"];

    public function fileHalaman()
    {
        return $this->hasMany(Halaman::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
