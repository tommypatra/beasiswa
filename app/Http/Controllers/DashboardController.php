<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        //untuk user id akun login : auth()->user()->id
        //dd(auth()->user());
        //dd(session()->all());
        //dd(session()->get("user")->fotoUser);
        //dd(session()->get("akses"));
        //dd(session()->get("akunId"));


        return view('admin.dashboard');
    }
}
