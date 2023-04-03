<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public static function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, tidak ditemukan"], "data" => []);
        $request['srchFld'] = (!$request['srchFld']) ? "id" : $request['srchFld'];
        $request['srchGrp'] = (!$request['srchGrp']) ? "where" : $request['srchGrp'];
        $request['srchVal'] = (!$request['srchVal']) ? auth()->user()->id : $request['srchVal'];

        $data = User::with(["pegawai"]);
        if ($request['srchGrp'] == 'like')
            $data->where($request['srchFld'], 'like', '%' . $request['srchVal'] . '%');
        else
            $data->where($request['srchFld'], $request['srchVal']);

        if ($data->count() > 0)
            $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data->get());

        return response()->json($retval);
    }
}
