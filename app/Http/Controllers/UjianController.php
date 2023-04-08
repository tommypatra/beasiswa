<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\Models\Beasiswa;
use App\Models\Ujian;

class UjianController extends Controller
{
    //
    public function index()
    {
        return view('admin.ujian');
    }

    public function init()
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        return response()->json($retval);
    }

    public function read(Request $request)
    {
        $data = Ujian::select(
            'id',
            'ujian',
            'keterangan',
            'aktif',
            'beasiswa_id'
        )
            ->with(["beasiswa.jenis"])
            ->where("beasiswa_id", $request['beasiswa_id']);

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif == "1") ? "Aktif" : "Tidak Aktif";
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
            'ujian' => 'required',
            'beasiswa_id' => 'required',
            'aktif' => 'required',
        ];
        $niceNames = [
            'beasiswa_id' => 'beasiswa',
        ];
        $datapost = $this->validate($request, $rules, [], $niceNames);
        $datapost['keterangan'] = $request['keterangan'];

        if (!$request['id']) {
            $retval = UjianController::create($request, $datapost)->getData();
        } else {
            $retval = UjianController::update($request, $datapost)->getData();
        }

        return response()->json($retval);
    }


    public function create(Request $request, $datapost)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $id = null;
        try {
            DB::beginTransaction();
            $id = Ujian::create($datapost)->id;
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
            $cari = Ujian::where("id", $id)->first();
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
            Ujian::whereIn('id', $ids)->delete();
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
            $data = Ujian::with(["beasiswa.jenis"])
                ->orderBy('ujian', 'ASC');
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
