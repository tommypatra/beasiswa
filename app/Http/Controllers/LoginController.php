<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $retval = array("status" => false, "messages" => ["login gagal, hubungi admin"]);
        $credentials = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|alpha_num|min:8',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            LoginController::setSession($credentials['email']);
            $retval = array("status" => true, "messages" => ["Login berhasil, user ditemukan. Tunggu sedang diarahkan ke laman dashboard"]);
        } else {
            $retval['messages'] = ["login tidak berhasil, user atau password tidak ditemukan"];
        }
        return response()->json($retval);
    }

    public static function setSession($email = null)
    {
        $det = \MyApp::detailLogin($email);
        //dd($det);
        session(
            [
                'akses' => $det['default']['akses'],
                'akunId' => $det['default']['akunId'],
                'foto' => $det['foto'],
                'admin' => $det['admin'],
                'mahasiswa' => $det['mahasiswa'],
                'pegawai' => $det['pegawai'],
            ]
        );
        return $det;
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }


    function setAkses($id = null, $akunId = null)
    {
        $retval = array("status" => false, "pesan" => ["gagal dilakukan"]);
        $akses = \MyApp::readAkses()->getData()->data;
        foreach ($akses as $i => $dp) {
            if ($dp->akunid == $akunId && $dp->id == $id) {
                $retval = array("status" => true, "pesan" => ["setup akses berhasil dilakukan"]);
                session(
                    [
                        'akses' => $dp->id,
                        'akunId' => $dp->akunid,
                    ]
                );
                break;
            }
        }
        return redirect('/dashboard');
        //return response()->json($retval);
    }
}
