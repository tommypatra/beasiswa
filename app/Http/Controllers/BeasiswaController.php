<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\Models\Beasiswa;
use App\Models\Jenis;

class BeasiswaController extends Controller
{
    //
    public function index()
    {
        return view('admin.beasiswa');
    }

    public function init()
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $dt = Jenis::where("aktif", "1")->get();
        if ($dt->count() > 0) {
            $retval['status'] = true;
            $retval['messages'] = ["data ditemukan"];
            $retval['data']['jenis'] = $dt;
        }
        return response()->json($retval);
    }

    public function read(Request $request)
    {
        $data = Beasiswa::select(
            'id',
            'nama',
            'daftar_mulai',
            'daftar_selesai',
            'verifikasi_mulai',
            'tahun',
            'verifikasi_selesai',
            'aktif',
            'pegawai_id',
            'jenis_id'
        )
            ->with(["jenis", "pegawai.user"]);

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif) ? "Aktif" : "Tidak Aktif";
            })
            ->editColumn('jenis', function ($row) {
                return $row->jenis->jenis;
            })
            ->addColumn('no', function ($row) {
                return '';
            })
            ->addColumn('daftar', function ($row) {
                $label = \MyApp::labeltanggal($row->daftar_mulai, $row->daftar_selesai);
                $retval = $row->daftar_mulai . ' s/d <br>' . $row->daftar_selesai . '<br>' . $label['labelbadge'];
                return $retval;
            })
            ->addColumn('verifikasi', function ($row) {
                $label = \MyApp::labeltanggal($row->verifikasi_mulai, $row->verifikasi_selesai);
                $retval = $row->verifikasi_mulai . ' s/d <br>' . $row->verifikasi_selesai . '<br>' . $label['labelbadge'];
                return $retval;
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
            ->rawColumns(['no', 'verifikasi', 'daftar', 'user', 'aktif', 'action', 'cek'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $rules = [
            'nama' => 'required',
            'jenis_id' => 'required',
            'tahun' => 'required',
            'sesi' => 'required',
            'peserta_ruang' => 'required',
            'ruang_sesi' => 'required',
            'daftar_mulai' => 'required',
            'daftar_selesai' => 'required|after_or_equal:daftar_mulai',
            'verifikasi_mulai' => 'required|after_or_equal:daftar_selesai',
            'verifikasi_selesai' => 'required|after_or_equal:verifikasi_mulai',
            'aktif' => 'required',
        ];

        $niceNames = [
            'peserta_ruang' => 'peserta per rungan',
            'ruang_sesi' => 'ruangan per sesi',
            'daftar_mulai' => 'required',
            'nama' => 'nama beasiswa',
            'jenis_id' => 'jenis',
            'daftar_mulai' => 'daftar mulai',
            'daftar_selesai' => 'daftar selesai',
            'verifikasi_mulai' => 'verifikasi mulai',
            'verifikasi_selesai' => 'verifikasi selesai',
        ];

        $datapost = $this->validate($request, $rules, [], $niceNames);
        $datapost['pegawai_id'] = session()->get("akunId");

        if (!$request['id']) {
            $retval = BeasiswaController::create($request, $datapost)->getData();
        } else {
            $retval = BeasiswaController::update($request, $datapost)->getData();
        }

        return response()->json($retval);
    }


    public function create(Request $request, $datapost)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $id = null;
        try {
            DB::beginTransaction();
            $id = Beasiswa::create($datapost)->id;
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
            $cari = Beasiswa::where("id", $id)->first();
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
            Beasiswa::whereIn('id', $ids)->delete();
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
            $data = Beasiswa::with(["jenis", "pegawai.user"])
                ->orderBy('daftar_mulai', 'DESC')
                ->where('aktif', 1);

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
