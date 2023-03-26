<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileBerita extends Model
{
    protected $guarded = ["id"];

    public function berita()
    {
        return $this->belongsTo(Berita::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
