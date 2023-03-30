<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuangPendaftar extends Model
{
    protected $guarded = ["id"];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function ruangBeasiswa()
    {
        return $this->belongsTo(RuangBeasiswa::class);
    }
}
