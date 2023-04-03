<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BeasiswaController;

class PendaftaranBeasiswaController extends Controller
{
    //
    public function init()
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);

        $request = request()->merge(['srchFld' => 'tahun', 'srchVal' => date("Y")]);
        $data = BeasiswaController::search($request)->getData();

        if ($data->status) {
            $retval['status'] = true;
            $retval['messages'] = ["data ditemukan"];
            $retval['data']['beasiswa'] = $data->data;
        }
        //dd($retval);
        return response()->json($retval);
    }
}
