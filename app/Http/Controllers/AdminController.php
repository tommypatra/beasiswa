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
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        if ($request['cari']) {
            $data = User::with(["admin.file"]);
            foreach ($request['cari'] as $i => $dp) {
                $srchFld = (!isset($dp['srchFld'])) ? "id" : $dp['srchFld'];
                $srchGrp = (!isset($dp['srchGrp'])) ? "where" : $dp['srchGrp'];
                $srchVal = (!isset($dp['srchVal'])) ? null : $dp['srchVal'];
                if ($srchVal) {
                    if ($srchGrp == 'like')
                        $data->where($srchFld, 'like', '%' . $srchVal . '%');
                    else
                        $data->where($srchFld, $srchVal);
                }
            }
            if ($data->count() > 0)
                $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data->get());
        }

        return response()->json($retval);
    }
}
