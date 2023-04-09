<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\Models\Verifikasi;
use App\Models\Pendaftar;
use App\Models\Upload;

class VerifikasiController extends Controller
{
    public function updateVerifikasi(Request $request)
    {
        $retval = array("status" => false, "insert" => false, "messages" => ["gagal, hubungi admin"]);
        $id = $request['pendaftar_id'];
        try {
            $datapost['verifikasi'] = $request['verifikasi'];
            DB::beginTransaction();
            $cari = Pendaftar::where("id", $id)->first();
            $cari->update($datapost);
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        $retval["id"] = $id;
        $retval["status"] = true;
        $retval["messages"] = ["Update verifikasi akhir berhasil dilakukan"];
        return response()->json($retval);
    }

    public function save(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $rules = [
            'upload_id' => 'required',
            'status' => 'required',
        ];
        $niceNames = [
            'upload_id' => 'file upload',
            'status' => 'status verifikasi',
        ];

        $datapost = $this->validate($request, $rules, [], $niceNames);
        $datapost['pegawai_id'] = session()->get("akunId");
        $datapost['keterangan'] = $request['keterangan'];
        if (!$request['id']) {
            $retval = VerifikasiController::create($request, $datapost)->getData();
        } else {
            $retval = VerifikasiController::update($request, $datapost)->getData();
        }

        return response()->json($retval);
    }


    public function create(Request $request, $datapost)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $id = null;
        try {
            DB::beginTransaction();
            $id = Verifikasi::create($datapost)->id;
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        $retval["id"] = $id;
        $retval["status"] = true;
        $retval["messages"] = ["Simpan data berhasil dilakukan"];
        return response()->json($retval);
    }

    public function update(Request $request, $datapost)
    {
        $retval = array("status" => false, "insert" => false, "messages" => ["gagal, hubungi admin"]);
        $id = $request['id'];
        try {
            DB::beginTransaction();
            $cari = Verifikasi::where("id", $id)->first();
            $cari->update($datapost);
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        $retval["id"] = $id;
        $retval["status"] = true;
        $retval["messages"] = ["Perubahan data berhasil dilakukan"];
        return response()->json($retval);
    }

    public function delete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            $ids = $request['id'];
            Verifikasi::whereIn('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["berhasil terhapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public static function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        if ($request['cari']) {
            $data = Upload::with(["file", "pendaftar.mahasiswa.user", "syarat", "verifikasi"])
                ->orderBy('id', 'ASC');
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
