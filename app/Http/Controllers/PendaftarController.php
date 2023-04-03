<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MahasiswaController;

use App\Http\Controllers\ProdiController;
use App\Http\Controllers\UploadController;

use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\Admin;
use App\Models\File;



class PendaftarController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.pendaftaran-akses', ["request" => $request]);
    }

    public function init()
    {
        $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => []);

        $det = LoginController::setSession(auth()->user()->email);

        $retval['data']['akses'] = $det;
        $retval['data']['akunLogin'] = UserController::myProfil();
        $retval['data']['jumlahAkun'] = UserController::jumlahAkun();
        //$retval['data']['prodi'] = ProdiController::daftarprodi();
        return response()->json($retval);
    }

    public function mahasiswaCreate(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $dp = $request['data'];
        $datapost = [
            'user_id' => auth()->user()->id,
            'nim' => $dp['nim'],
            'idprodi' => $dp['idprodi'],
            'prodi' => $dp['prodi'],
            'fakultas' => $dp['fakultas'],
            'bagian_id' => '3',
            'aktif' => '1',
        ];
        try {
            DB::beginTransaction();
            $id = Mahasiswa::create($datapost)->id;
            $retval["status"] = true;
            $retval["messages"] = ["Simpan data berhasil dilakukan"];
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }

        return response()->json($retval);
    }

    public function pegawaiCreate(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $dp = $request['data'];
        $datapost = [
            'user_id' => auth()->user()->id,
            'no_pegawai' => $dp['nip'],
            'bagian_id' => '2',
            'aktif' => '1',
        ];
        try {
            DB::beginTransaction();
            $id = Pegawai::create($datapost)->id;
            $retval["status"] = true;
            $retval["messages"] = ["Simpan data berhasil dilakukan"];
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }

        return response()->json($retval);
    }

    public function adminCreate(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $id = null;
        $insert = true;
        if ($request['id']) {
            $insert = false;
        }
        $request['user_id'] = auth()->user()->id;
        $request['bagian_id'] = 1;

        $rules = [
            'bagian_id' => 'required',
        ];
        if ($insert)
            $rules['user_id'] = 'unique:admins,user_id';
        else
            $rules['user_id'] = 'required';

        $niceNames = [
            'user_id' => 'user',
            'bagian_id' => 'bagian',
        ];

        $request['aktif'] = "1";
        $datapost = $this->validate($request, $rules, [], $niceNames);
        $retval['insert'] = $insert;
        try {
            DB::beginTransaction();

            $resUpload = PendaftarController::upload($request, 'ktm')->getData();
            if ($resUpload->status) {
                $datapost['file_id'] = $resUpload->id;
            }

            if ($insert) {
                $id = Admin::create($datapost)->id;
            } else {
                $id = $request['id'];
                $cari = Admin::where("id", $request['id'])->first();
                $cari->update($datapost);
            }

            $retval["status"] = true;
            $retval["messages"] = ["Simpan data berhasil dilakukan"];
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        return response()->json($retval);
    }

    public function update(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, data tidak ditemukan"], "data" => []);
        try {
            $data = Mahasiswa::where('id', $request['id'])
                ->with(["user"])
                ->first();
            if ($data)
                $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function delete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            $ids = $request['id'];
            Mahasiswa::whereIn('mou_id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["data berhasil dihapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    // public function search(Request $request)
    // {
    //     $retval = array("status" => false, "messages" => ["maaf, tidak ditemukan"], "data" => []);
    //     $data = User::with(["mahasiswa"])
    //         ->where('nama', 'like', '%' . $request['cari'] . '%')
    //         ->orWhere('email', 'like', '%' . $request['cari'] . '%')
    //         ->get();
    //     if ($data->mahasiswa->count() > 0) {
    //         $retval = array("status" => true, "messages" => ["data ditemukan"], $data);
    //     }
    //     return response()->json($retval);
    // }


    public static function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $request['srchFld'] = (!$request['srchFld']) ? "id" : $request['srchFld'];
        $request['srchGrp'] = (!$request['srchGrp']) ? "where" : $request['srchGrp'];

        if ($request['srchVal']) {
            $data = User::with(["mahasiswa"]);
            if ($request['srchGrp'] == 'like')
                $data->where($request['srchFld'], 'like', '%' . $request['srchVal'] . '%');
            else
                $data->where($request['srchFld'], $request['srchVal']);

            if ($data->count() > 0)
                $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data->get());
        }

        return response()->json($retval);
    }

    public function deleteUpload(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);

        try {
            $id = explode(":", $request['id']);
            $file_id = $id[0];
            $akun_id = $id[1];

            DB::beginTransaction();
            $cari = File::where("id", $file_id)->first();

            if (isset($cari->id)) {

                $datapost = ["file_id" => null];
                switch ($request['grup']) {
                    case 'mahasiswa':
                        $vcari = Mahasiswa::where("id", $akun_id)->first();
                        break;
                    case 'pegawai':
                        $vcari = Pegawai::where("id", $akun_id)->first();
                        break;
                    default:
                        $vcari = Admin::where("id", $akun_id)->first();
                        break;
                }
                $vcari->update($datapost);

                $file = $cari->source;
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
                $cari->delete();


                $retval = array("status" => true, "messages" => ["hapus file berhasil dilakukan"]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        return response()->json($retval);
    }

    public function upload(Request $request, $fldr)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);

        if ($request->hasFile('fileupload')) {
            $this->validate($request, [
                'fileupload' => ['mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024'],
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
        return response()->json($retval);
    }
}
