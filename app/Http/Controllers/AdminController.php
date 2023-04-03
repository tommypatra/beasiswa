<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // public static function search(Request $request)
    // {
    //     $retval = array("status" => false, "messages" => ["maaf, tidak ditemukan"], "data" => []);
    //     if (!$request['field'])
    //         $request['field'] = "id";
    //     if (!$request['value'])
    //         $request['value'] = auth()->user()->id;

    //     $data = User::with(["admin.file"])
    //         ->where($request['field'], $request['value'])
    //         ->get();
    //     if ($data->count() > 0) {
    //         $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data);
    //     }
    //     return response()->json($retval);
    // }

    public static function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, tidak ditemukan"], "data" => []);
        $request['srchFld'] = (!$request['srchFld']) ? "id" : $request['srchFld'];
        $request['srchGrp'] = (!$request['srchGrp']) ? "where" : $request['srchGrp'];
        $request['srchVal'] = (!$request['srchVal']) ? auth()->user()->id : $request['srchVal'];

        $data = User::with(["admin.file"]);
        if ($request['srchGrp'] == 'like')
            $data->where($request['srchFld'], 'like', '%' . $request['srchVal'] . '%');
        else
            $data->where($request['srchFld'], $request['srchVal']);

        if ($data->count() > 0)
            $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data->get());

        return response()->json($retval);
    }
}
