<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{

    protected $guarded = ["id"];

    public function berita()
    {
        return $this->hasMany(Berita::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
