<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\Models\Beasiswa;
use App\Models\RuangBeasiswa;
use App\Models\RuangPenguji;
use App\Models\Ujian;
use App\Models\Ruang;
use App\Models\Pegawai;

use App\Http\Controllers\UjianController;

class RuangBeasiswaController extends Controller
{
    //
    public function index()
    {
        return view('admin.ruangBeasiswa');
    }

    public function init()
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);

        $data = Ruang::where('aktif', 1)
            ->orderBy('ruang', 'ASC');
        if ($data->count() > 0) {
            $retval['status'] = true;
            $retval['messages'] = ['ditemukan'];
            $retval['data']['ruang'] = $data->get();
        }

        $data = Pegawai::with("user")->where('aktif', 1)
            ->orderBy('no_pegawai', 'ASC');
        if ($data->count() > 0) {
            $retval['status'] = true;
            $retval['messages'] = ['ditemukan'];
            $retval['data']['pegawai'] = $data->get();
        }

        return response()->json($retval);
    }

    public function read(Request $request)
    {
        $data = RuangBeasiswa::select(
            'id',
            'ruang_id',
            'beasiswa_id',
        )
            ->with(["ruang", "ruangPenguji.pegawai.user", "beasiswa.jenis"])
            ->where("beasiswa_id", $request['beasiswa_id']);

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('no', function ($row) {
                return '';
            })
            ->addColumn('ruangujian', function ($row) {
                return $row->ruang->ruang;
            })
            ->addColumn('penguji', function ($row) {
                $retval = "";
                if (count($row->ruangpenguji) > 0) {
                    $retval = "<ul>";
                    foreach ($row->ruangpenguji as $i => $dp) {
                        $retval .= '<li>' . $dp->pegawai->user->nama . ' <a href="javascript:;" class="btn-hapus-pegawai" data-id="' . $dp->id . '"><span class="material-icons">delete_forever</span></a></li>';
                    }
                    $retval .= "</ul>";
                }
                return $retval;
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-success btn-ganti" data-id="' . $row->id . '">
                            <span class="material-icons">edit</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-warning btn-penguji" data-id="' . $row->id . '">
                            <span class="material-icons">assignment_ind</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '">
                            <span class="material-icons">delete_forever</span>
                        </button>';
                return $btn;
            })
            ->rawColumns(['no', 'jenis', 'penguji', 'aktif', 'action', 'cek'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $rules = [
            'ruang_id' => 'required',
            'beasiswa_id' => 'required',
        ];
        $niceNames = [
            'ruang_id' => 'ruangan',
            'beasiswa_id' => 'beasiswa',
        ];

        if (!$request['id'] || $request['insertpegawai']) {
            $rules['pegawai_id'] = 'required';
            $niceNames['pegawai_id'] = 'pegawai';
        }

        $datapost = $this->validate($request, $rules, [], $niceNames);

        if (!$request['id']) {
            $retval = RuangBeasiswaController::create($request, $datapost)->getData();
        } else {
            $retval = RuangBeasiswaController::update($request, $datapost)->getData();
        }

        return response()->json($retval);
    }


    public function create(Request $request, $datapost)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $id = null;
        try {
            DB::beginTransaction();
            $dtpeg = $datapost['pegawai_id'];
            unset($datapost['pegawai_id']);
            $id = RuangBeasiswa::create($datapost)->id;
            if (count($dtpeg) > 0)
                foreach ($dtpeg as $idpeg) {
                    $datapost = array(
                        'ruang_beasiswa_id' => $id,
                        'pegawai_id' => $idpeg,
                    );
                    $tmpid = RuangPenguji::create($datapost)->id;
                }
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
            $cari = RuangBeasiswa::where("id", $id)->first();
            $cari->update($datapost);
            //jika kosong data maka boleh insert pegawai
            if ($request['insertpegawai'] == 1) {
                $dtpeg = $request['pegawai_id'];
                if (count($dtpeg) > 0)
                    foreach ($dtpeg as $idpeg) {
                        $datapost = array(
                            'ruang_beasiswa_id' => $id,
                            'pegawai_id' => $idpeg,
                        );
                        $tmpid = RuangPenguji::create($datapost)->id;
                    }
            }
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
            RuangPenguji::whereIn('ruang_beasiswa_id', $ids)->delete();
            RuangBeasiswa::whereIn('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["berhasil terhapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function deletePegawai(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            RuangPenguji::where('id', $request['id'])->delete();
            $retval = array("status" => true, "messages" => ["berhasil terhapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public static function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $request['srchFld'] = (!$request['srchFld']) ? "id" : $request['srchFld'];
        $request['srchGrp'] = (!$request['srchGrp']) ? "where" : $request['srchGrp'];

        if ($request['srchVal']) {
            $data = RuangBeasiswa::with(["ruang", "ruangPenguji.pegawai.user", "beasiswa.jenis"])
                ->orderBy('beasiswa_id', 'ASC');
                ->orderBy('ruang_id', 'ASC');
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
