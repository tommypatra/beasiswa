<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\Models\Beasiswa;
use App\Models\Syarat;

class SyaratController extends Controller
{
    //
    public function index()
    {
        return view('admin.syarat');
    }

    public function init()
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $dt = Beasiswa::select('tahun')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->where("aktif", "1")->get();
        if ($dt->count() > 0) {
            $retval['status'] = true;
            $retval['messages'] = ["data ditemukan"];
            $retval['data']['beasiswa'] = $dt;
        }
        return response()->json($retval);
    }

    public function read(Request $request)
    {
        $data = Syarat::select(
            'id',
            'nama',
            'keterangan',
            'aktif',
            'wajib',
            'beasiswa_id'
        )
            ->with(["beasiswa.jenis"])
            ->where("beasiswa_id", $request['beasiswa_id']);

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif == "1") ? "Aktif" : "Tidak Aktif";
            })
            ->editColumn('wajib', function ($row) {
                return ($row->wajib) ? "Wajib" : "Pilihan";
            })
            ->editColumn('jenis', function ($row) {
                return $row->beasiswa->jenis->jenis;
            })
            ->addColumn('no', function ($row) {
                return '';
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-success btn-ganti" data-id="' . $row->id . '">
                            <span class="material-icons">edit</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '">
                            <span class="material-icons">delete_forever</span>
                        </button>';
                return $btn;
            })
            ->rawColumns(['no', 'jenis', 'aktif', 'action', 'cek'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $rules = [
            'nama' => 'required',
            'beasiswa_id' => 'required',
            'wajib' => 'required',
            'aktif' => 'required',
        ];
        $niceNames = [
            'beasiswa_id' => 'beasiswa',
        ];
        $datapost = $this->validate($request, $rules, [], $niceNames);
        $datapost['keterangan'] = $request['keterangan'];

        if (!$request['id']) {
            $retval = SyaratController::create($request, $datapost)->getData();
        } else {
            $retval = SyaratController::update($request, $datapost)->getData();
        }

        return response()->json($retval);
    }


    public function create(Request $request, $datapost)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $id = null;
        try {
            DB::beginTransaction();
            $id = Syarat::create($datapost)->id;
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
            $cari = Syarat::where("id", $id)->first();
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
            Syarat::whereIn('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["berhasil terhapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }

    public function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $data = Syarat::with(["beasiswa.jenis"]);
        if ($request['srchFld']) {
            if ($request['srchGrp'] == 'like')
                $data->where($request['srchFld'], 'like', '%' . $request['srchVal'] . '%');
            else
                $data->where($request['srchFld'], $request['srchVal']);
        }

        if ($data->count() > 0)
            $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data->get());

        return response()->json($retval);
    }
}
