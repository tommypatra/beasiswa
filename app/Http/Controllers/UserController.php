<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\FotoUser;
use App\Models\File;

class UserController extends Controller
{
    public function profil(Request $request)
    {
        return view('admin.profil', ["request" => $request]);
    }

    public static function jumlahAkun()
    {
        $user = User::where('aktif', '1')->count();
        $admin = Admin::where('aktif', '1')->count();
        $mahasiswa = Mahasiswa::where('aktif', '1')->count();
        $pegawai = Pegawai::where('aktif', '1')->count();
        return response()->json(["user" => $user, "admin" => $admin, "mahasiswa" => $mahasiswa, "pegawai" => $pegawai]);
    }

    public static function myProfil()
    {
        $akun = User::select('id', 'glrdepan', 'glrbelakang', 'alamat', 'tentang', 'fb', 'ig', 'twitter', 'email', 'nama', 'nohp', 'tanggallahir', 'tempatlahir', 'aktif')->where('email', auth()->user()->email)
            ->with(['fotoUser.file'])
            ->first();
        return response()->json($akun);
    }

    public function update(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $request['id'] = auth()->user()->id;
        $request['bagian_id'] = 1;

        $rules = [
            'nama' => 'required',
            'nohp' => 'required',
            'tempatlahir' => 'required',
            'tanggallahir' => 'required',
            'alamat' => 'required',
        ];

        $niceNames = [
            'nama' => 'nama lengkap',
            'nohp' => 'no. handphone',
            'tempatlahir' => 'tempat lahir',
            'tanggallahir' => 'tanggal lahir',
            'alamat' => 'alamat',
        ];

        $datapost = $this->validate($request, $rules, [], $niceNames);
        $datapost['tentang'] = $request['tentang'];
        $datapost['glrdepan'] = $request['glrdepan'];
        $datapost['glrbelakang'] = $request['glrbelakang'];

        $resUpload = UserController::upload($request, 'fotoprofil', 'jpeg,png,jpg,gif,svg')->getData();
        if ($resUpload->status) {
            try {
                DB::beginTransaction();
                $datapost2 = [
                    'file_id' => $resUpload->id,
                    'user_id' => auth()->user()->id
                ];
                FotoUser::create($datapost2);
                DB::commit();
            } catch (\Throwable $e) {
                $retval['messages'] = [$e->getMessage()];
                DB::rollBack();
            }
        }

        try {
            DB::beginTransaction();
            $cari = User::where("id", $request['id'])->first();
            $cari->update($datapost);


            $retval["status"] = true;
            $retval["messages"] = ["Simpan data berhasil dilakukan"];
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        return response()->json($retval);
    }

    public function cekPassword()
    {
        $retval['status'] = false;
        $akun = User::select('password')->where('email', auth()->user()->email)
            ->first();
        if ($akun->password != "")
            $retval['status'] = true;
        return response()->json($retval);
    }

    public function updatePassword(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $tambah = true;
        if ($request['status'] == "1")
            $tambah = false;

        $rules = [
            'password' => 'required|alpha_num|min:8',
        ];
        $niceNames = [
            'password' => 'password baru',
        ];

        //jika sudah ada password lama
        if (!$tambah) {
            $rules['passlama'] = 'required|alpha_num|min:8';
            $niceNames['passlama'] = 'password lama';

            //untuk mengecek akun lama
            $cekPassLama = [
                'id' => auth()->user()->id,
                'password' => $request['passlama'],
            ];
            if (!Auth::attempt($cekPassLama)) {
                $retval["messages"] = ["Password lama anda salah"];
                return response()->json($retval);
            }
        }
        //untuk validasi ada tidaknya inputan
        $datapost = $this->validate($request, $rules, [], $niceNames);

        //untuk update password
        try {
            DB::beginTransaction();
            $datapost['password'] = Hash::make($request['password']);
            $cari = User::where("id", auth()->user()->id)->first();
            $cari->update($datapost);

            $retval["status"] = true;
            $retval["messages"] = ["Simpan password berhasil dilakukan"];
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        return response()->json($retval);
    }

    public function upload(Request $request, $fldr, $mime)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);

        if ($request->hasFile('fileupload')) {
            $this->validate($request, [
                'fileupload' => ['mimes:' . $mime, 'max:1024'],
            ]);
            try {
                $file = $request->file('fileupload');
                $ext = $file->getClientOriginalExtension();
                $det =   [
                    "originalName" => $file->getClientOriginalName(),
                    "size" => ceil($file->getSize() / 1000),
                    "mime" => $file->getMimeType(),
                    "ext" => $ext,
                ];
                $datapost['user_id'] = auth()->user()->id;
                $datapost['detail'] = json_encode($det);
                $datapost['is_image'] = "1";
                if (strtolower($ext) == "pdf") {
                    $datapost['is_image'] = "0";
                }
                $destinationPath = 'uploads/' . $fldr . '/' . date('Y') . '/' . date('m');
                $datapost['is_file'] = "1";
                $datapost['path'] = $file->store($destinationPath);

                DB::beginTransaction();
                $id = File::create($datapost)->id;
                $retval["id"] = $id;
                $retval["status"] = true;
                $retval["messages"] = ["Simpan data berhasil dilakukan"];
                DB::commit();
            } catch (\Throwable $e) {
                $retval['messages'] = [$e->getMessage()];
                DB::rollBack();
            }
        }
        //dd($retval);
        return response()->json($retval);
    }

    public static function labelAkses()
    {
        $hakakses = \MyApp::readAkses()->getData()->data;
        $html = "<ul>";
        foreach ($hakakses as $i => $dp) {
            $html .= "<li><a href='" . route('set-akses', [$dp->id, $dp->akunid]) . "'>" . $dp->label . "</a></li>";
        }
        $html .= "</ul>";

        $retval['html'] = $html;
        return response()->json($retval);
    }

    public static function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $request['srchFld'] = (!$request['srchFld']) ? "id" : $request['srchFld'];
        $request['srchGrp'] = (!$request['srchGrp']) ? "where" : $request['srchGrp'];

        if ($request['srchVal']) {
            $data = User::orderBy('nama', 'ASC');

            if ($request['srchGrp'] == 'like')
                $data->where($request['srchFld'], 'like', '%' . $request['srchVal'] . '%');
            else
                $data->where($request['srchFld'], $request['srchVal']);

            if ($data->count() > 0)
                $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data->get());
        }

        return response()->json($retval);
    }
}
