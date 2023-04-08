<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\Models\Pendaftar;

class PesertaController extends Controller
{
    //
    public function index()
    {
        return view('admin.peserta');
    }

    public function init()
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        return response()->json($retval);
    }

    public function read(Request $request)
    {
        $data = Pendaftar::select(
            'id',
            'beasiswa_id',
            'mahasiswa_id',
            'verifikasi',
            'created_at',
            'keterangan',
        )
            ->with(["mahasiswa.user.fotouser.file", "beasiswa.jenis", "beasiswa.syarat.upload" => function ($upload) {
                $upload->with("file", "verifikasi");
            }])
            ->where("beasiswa_id", $request['beasiswa_id']);

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('no', function ($row) {
                return '';
            })
            ->editColumn('nama', function ($row) {
                return $row->mahasiswa->user->nama;
            })
            ->editColumn('nim', function ($row) {
                return $row->mahasiswa->nim;
            })
            ->editColumn('prodi', function ($row) {
                return $row->mahasiswa->prodi;
            })
            ->editColumn('jenis', function ($row) {
                return $row->beasiswa->jenis->jenis;
            })
            ->editColumn('waktu_daftar', function ($row) {
                return $row->created_at->toDateTimeString();
            })
            ->editColumn('verifikasi', function ($row) {
                if ($row->verifikasi === null)
                    $retval = '<span class="badge bg-gradient-warning">Belum</span>';
                else {
                    $sts = "success";
                    $lbl = "MS";
                    if (!$row->verifikasi) {
                        $sts = "danger";
                        $lbl = "TMS";
                    }
                    $retval = '<span class="badge bg-gradient-' . $lblsts . '">Belum</span>';
                    $retval .= '<div class="text-sm">' . $row->keterangan . '</div>';
                }
                return $retval;
            })
            ->editColumn('file_upload', function ($row) {
                $retval = '';
                $syarats = $row->beasiswa->syarat;
                // $file = $row->upload;
                $retval = '<div id="upload-' . $row->id . '">';

                if (count($syarats) > 0) {
                    $retval .= '<ul class="text-sm">';
                    foreach ($syarats as $i => $dp) {
                        $wajib = ($dp->wajib) ? "[wajib]" : "[boleh kosong]";
                        $retval .= '<li>' . $dp->nama . ' ' . $wajib . '</li>';
                        $files = $dp->upload;
                        $retval .= '<ul class="text-xs">';
                        if (count($files) > 0) {
                            foreach ($files as $i => $df) {
                                $detfile = json_decode($df->file->detail);
                                $url = asset('storage') . '/' . $df->file->path;
                                $retval .= '<li>
                                                <a href="' . $url . '" target="_blank">' . $detfile->originalName . '</a>
                                                <a href="javascript:;" type="button" class="btn btn-sm btn-verifikasi" data-upload_id="' . $df->id . '">
                                                    <span class="material-icons">playlist_add_check</span>
                                                </a>
                                            </li>';
                            }
                        } else {
                            $retval .= '<li>belum upload</li>';
                        }
                        $retval .= '</ul>';
                    }
                    $retval .= '</ul>';
                }
                $retval .= '</div>';
                return $retval;
            })
            ->editColumn('mahasiswa', function ($row) {
                $foto = isset($row->mahasiswa->user->fotouser[0]) ? $row->mahasiswa->user->fotouser[0]->file : null;
                $urlfoto = 'images/user-avatar.png';
                if ($foto) {
                    $urlfoto = asset('storage') . '/' . $foto->path;
                }
                $retval = '
                <div class="list-group-item border-0 d-flex align-items-center px-0 mb-2 pt-0">
                    <div class="avatar me-3">
                        <img src="' . $urlfoto . '" alt="kal" class="border-radius-lg shadow">
                    </div>
                        <div class="d-flex align-items-start flex-column justify-content-center">
                        <h6 class="mb-0">' . $row->mahasiswa->user->nama . '</h6>
                        <p class="mb-0 text-xs">' . $row->mahasiswa->nim . '</p>
                        <p class="mb-0 text-xs">' . $row->mahasiswa->prodi . '</p>
                    </div>
                </div>
                <div class="mb-3 text-xs"><span class="material-icons">access_time</span> ' . $row->created_at->toDateTimeString() . '</div>';
                if (count($row->beasiswa->syarat) > 0)
                    $retval .= '
                        <button type="button" class="btn btn-sm btn-success btn-verifikasi" data-pendaftar_id="' . $row->id . '">
                            <span class="material-icons">playlist_add_check</span> Verifikasi
                        </button>';
                return $retval;
            })
            ->rawColumns(['no', 'file_upload', 'mahasiswa', 'verifikasi', 'cek'])
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
            $data = Pendaftar::with(["mahasiswa.user.fotouser.file", "beasiswa.jenis", "beasiswa.syarat.upload" => function ($upload) {
                $upload->with("file", "verifikasi");
            }])
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
