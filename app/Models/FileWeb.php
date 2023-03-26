<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileWeb extends Model
{
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
