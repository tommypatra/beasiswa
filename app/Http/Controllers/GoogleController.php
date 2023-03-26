<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Mahasiswa;
use App\Http\Controllers\LoginController;

class GoogleController extends Controller
{
    // untuk login google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $user_google    = Socialite::driver('google')->user();

            $extemail = \MyApp::extractemail($user_google->getEmail());

            if ($extemail[2] != 'iainkendari.ac.id') {
                $request->session()->flash('pesan', 'Hanya boleh menggunakan email institusi @iainkendari.ac.id');
                return redirect()->route('login');
            }

            $user = User::where('email', $user_google->getEmail())->first();
            if (!$user) {
                $user = User::Create([
                    'email' => $user_google->getEmail(),
                    'nama' => $user_google->getName(),
                    'kel' => 'L',
                    'tanggallahir' => date('Y-m-d'),
                    'aktif' => '1',
                ]);
            }
            LoginController::setSession($user_google->getEmail());
            Auth::login($user);
            return redirect()->route('dashboard');
        } catch (\Throwable $e) {
            $request->session()->flash('pesan', $e->getMessage());
            return redirect()->route('login');
        }
    }
}
