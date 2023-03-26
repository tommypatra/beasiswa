<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;

class ProdiController extends Controller
{
    public static function daftarprodi()
    {
        return Prodi::with(['fakultas'])->where('aktif', '1')->get();
    }
}
