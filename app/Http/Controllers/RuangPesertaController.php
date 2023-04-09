<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\Models\RuangBeasiswa;
// use App\Models\Syarat;

class RuangPesertaController extends Controller
{
    //
    public function index()
    {
        return view('admin.ruangPeserta');
    }

    public function init()
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        return response()->json($retval);
    }

    public function read(Request $request)
    {
        $data = RuangBeasiswa::select(
            'id',
            'ruang_id',
            'beasiswa_id'
        )
            ->with(["ruang", "ruangPenguji.pegawai.user", "ruangPendaftar.pendaftar.mahasiswa.user"])
            ->where("beasiswa_id", $request['beasiswa_id']);

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('ruang', function ($row) {
                return ($row->ruang) ? ($row->ruang->ruang) : "";
            })
            ->editColumn('penguji', function ($row) {
                $retval = '';
                if (count($row->ruangpenguji) > 0) {
                    $retval = '<ul>';
                    foreach ($row->ruangpenguji as $i => $dp) {
                        $pegawai = $dp->pegawai->user;
                        $retval .= '<li>' . $pegawai->nama . '</li>';
                    }
                    $retval .= '</ul>';
                }
                return $retval;
            })
            ->editColumn('mahasiswa', function ($row) {
                $retval = '';
                if (count($row->ruangpendaftar) > 0) {
                    $retval = '<ul>';
                    foreach ($row->ruangpendaftar as $i => $dp) {
                        $mahasiswa = $dp->pendaftar->mahasiswa->user;
                        $retval .= '<li>' . $pegawai->nama . '</li>';
                    }
                    $retval = '</ul>';
                }
                return $retval;
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
            ->rawColumns(['no', 'penguji', 'mahasiswa', 'action', 'cek'])
            ->make(true);
    }

    public static function formPembagian(string $id)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $retval['data']['id'] = $id;
        return response()->json($retval);
    }


    public static function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        if ($request['cari']) {
            $data = RuangBeasiswa::select(
                'id',
                'ruang_id',
                'beasiswa_id'
            )
                ->with(["ruang", "ruangPenguji.pegawai.user", "ruangPendaftar.pendaftar.mahasiswa.user"])
                ->where("beasiswa_id", $request['beasiswa_id'])
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

    public function pesertaSimulasi(Request $request)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $beasiswa_id = $request['beasiswa_id'];

        $data = DB::table('ruang_beasiswas as rb')
            ->join('ruangs as r', 'r.id', '=', 'rb.ruang_id')
            ->select(
                'rb.id as ruang_beasiswa_id',
                'rb.ruang_id',
                'rb.beasiswa_id',
                'r.ruang',
            )
            ->where('rb.beasiswa_id', $beasiswa_id)
            ->orderBy('r.ruang', 'ASC')
            ->get();
        if ($data->count() > 0) {
            $retval = array("status" => true, "messages" => ["ditemukan"]);
            $retval['data']['ruang'] = $data;
        }

        $data = DB::table('ruang_pengujis as rp')
            ->join('ruang_beasiswa as rb', 'rb.id', '=', 'rp.ruang_beasiswa_id')
            ->join('pegawais as p', 'p.id', '=', 'rp.pegawai_id')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->select(
                'rp.ruang_beasiswa_id',
                'rp.pegawai_id',
                'p.no_pegawai',
                'TRIM(CONCAT(u.glrdepan," ",u.nama," ",u.glrbelakang)) as nama',
                'u.emali',
                'u.nohp',
                'u.alamat',
                'u.kel',
            )
            ->where('rb.beasiswa_id', $beasiswa_id)
            ->orderBy('rp.ruang_beasiswa_id', 'ASC')
            ->orderBy('u.nama', 'ASC')
            ->get();
        if ($data->count() > 0) {
            $retval = array("status" => true, "messages" => ["ditemukan"]);
            $retval['data']['penguji'] = $data;
        }

        $data = DB::table('pendaftars as ps')
            ->join('mahasiswas as m', 'm.id', '=', 'ps.mahasiswa_id')
            ->join('users as u', 'u.id', '=', 'm.user_id')
            ->join('ruang_pendaftars as r', 'r.pendaftar_id', '=', 'ps.pendaftar_id')
            ->select(
                'ps.id as pendaftar_id',
                'ps.mahasiswa_id',
                'ps.beasiswa_id',
                'ps.verifikasi',
                'ps.keterangan',
                'TRIM(CONCAT(u.glrdepan," ",u.nama," ",u.glrbelakang)) as nama',
                'u.emali',
                'u.nohp',
                'u.alamat',
                'u.kel',
                'm.nim',
                'm.prodi',
                'm.idprodi',
                'm.fakultas',
                'r.ruang_beasiswa_id'
            )
            ->where('ps.beasiswa_id', $beasiswa_id)
            ->where('ps.verifikasi', true)
            ->orderBy('u.kel', 'ASC')
            ->orderBy('m.fakultas', 'ASC')
            ->orderBy('m.idprodi', 'ASC')
            ->orderBy('m.nim', 'ASC')
            ->get();
        if ($data->count() > 0) {
            $retval = array("status" => true, "messages" => ["ditemukan"]);
            $retval['data']['peserta'] = $data;
        }
        return response()->json($retval);
    }

    public function simulasi(Request $request)
    {

        $init = RuangPesertaController::pesertaSimulasi($request);
        dd($init);
        // $idkkn = $request['idkkn'];

        //     $vCari = array(
        //         array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        //         array("cond" => "where", "fld" => "pm.id IS NULL", "val" => null),
        //     );

        //     $pesertakkn = $this->dataweb->pesertakkn($vCari, 0, 0, "u.kel ASC, fak.id ASC, prodi.id ASC");

        //     $simulai_kelompok = [];
        //     if ($pesertakkn['status']) {
        //         $kelompok = 1;
        //         $indexanggota = 1;
        //         foreach ($pesertakkn['db'] as $i => $dp) {
        //             $simulai_kelompok[$kelompok][] = $dp;
        //             $indexanggota++;
        //             $kelompok++;
        //             if ($indexanggota > $jumkel) {
        //                 $kelompok = 1;
        //                 $indexanggota = 1;
        //             }
        //         }
        //     }

        //     //debug($simulai_kelompok);
        //     foreach ($simulai_kelompok as $i => $kel) {
        //         echo "Kelompok-" . $i . "<br>";
        //         foreach ($kel as $j => $dp) {
        //             echo ($j + 1) . ". " . $dp['nama'] . " (" . $dp['kel'] . ") " . $dp['prodi'] . "<br>";
        //         }
        //     }
        // }       
    }
}
