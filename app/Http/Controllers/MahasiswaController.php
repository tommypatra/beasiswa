<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MahasiswaController extends Controller
{
    //
    public static function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, tidak ditemukan"], "data" => []);
        if (!$request['field'])
            $request['field'] = "id";
        if (!$request['value'])
            $request['value'] = auth()->user()->id;

        $data = User::with(["mahasiswa.fileUpload"])
            ->where($request['field'], $request['value'])
            ->get();
        //$mahasiswa = isset($data[0]->mahasiswa[0]) ? $data[0]->mahasiswa[0] : [];
        if ($data->count() > 0) {
            $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data);
        }
        return response()->json($retval);
    }
}
