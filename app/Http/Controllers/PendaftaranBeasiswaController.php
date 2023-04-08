<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\SyaratController;
use App\Models\Pendaftar;
use App\Models\File;
use App\Models\Upload;
use App\Models\Beasiswa;


class PendaftaranBeasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.pendaftaranbeasiswa');
    }

    public function formUpload(Request $request)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => [], "html" => "<p>Tidak Ditemukan</p>");
        $pendaftar_id = $request['pendaftar_id'];
        $beasiswa_id = $request['beasiswa_id'];
        $beasiswa = Beasiswa::with([
            "jenis", "pegawai.user", "ujian", "syarat.upload" => function ($upload) use ($pendaftar_id) {
                $upload->where('pendaftar_id', $pendaftar_id)
                    ->with("file");
            },
            "pendaftar" => function ($pendaftar) {
                $pendaftar->where('mahasiswa_id', session()->get("akunId"));
                $pendaftar->with(['mahasiswa']);
            },
        ])
            ->where('id', $beasiswa_id)
            ->orderBy('daftar_mulai', 'DESC')
            ->first();

        if ($beasiswa->syarat->count() > 0) {
            $retval['status'] = true;
            $retval['messages'] = ["data ditemukan"];
            $retval['data']['beasiswa'] = $beasiswa;
            $tmphtml = "";
            $pendaftar_id = $beasiswa->pendaftar[0]->id;
            foreach ($beasiswa->syarat as $i => $dp) {
                $dp['upload'];
                $tmphtml .= '<div class="col-md-6 col-6 mb-3">
                                <div class="card">
                                    <div class="card-header mx-4 p-3 text-center">
                                        <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                                            <i class="material-icons opacity-10 act-upload">upload</i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                        <h6 class="text-center mb-0">' . $dp['nama'] . '</h6>
                                        <span class="text-xs">' . $dp['keterangan'] . '</span>';
                $tmphtml .= '           <hr class="horizontal dark my-3">
                                        <input style="font-size:10px" type="file" class="mengupload-syarat" data-beasiswa_id="' . $dp['beasiswa_id'] . '" data-syarat_id="' . $dp['id'] . '" data-pendaftar_id="' . $pendaftar_id . '" name="fileupload" >';
                if (count($dp['upload']) > 0) {
                    $tmphtml .= '<ul class="mt-3" style="text-align: left;">';
                    foreach ($dp['upload'] as $j => $df) {
                        $detfile = json_decode($df->file->detail);
                        $url = asset('storage') . '/' . $df->file->path;
                        $tmphtml .= '<li>';
                        $tmphtml .= '<a href="' . $url . '" target="_blank">' . $detfile->originalName . '</a>';
                        $tmphtml .= '<a href="javascript:;" class="btn-hapus-upload" data-id="' . $df->file->id . '"><span class="material-icons">delete_forever</span></a>';
                        $tmphtml .= '</li>';
                    }
                    $tmphtml .= '</ul>';
                }
                $tmphtml .= '       </div>
                                </div>
                            </div>';
            }
            $retval['html'] = $tmphtml;
        }

        //dd($retval);
        return response()->json($retval);
    }

    public function init()
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $cari = [
            "cari" => [
                ['srchFld' => 'tahun', 'srchVal' => date("Y")],
                ['srchFld' => 'aktif', 'srchVal' => 1],
            ]
        ];
        $request = request()->merge($cari);
        $beasiswa = BeasiswaController::search($request)->getData();
        if ($beasiswa->status) {
            $retval['status'] = true;
            $retval['messages'] = ["data ditemukan"];
            $retval['data']['beasiswa'] = $beasiswa->data;
        }

        //dd($retval);
        return response()->json($retval);
    }

    public function save(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $rules = [
            'beasiswa_id' => 'required',
        ];
        $niceNames = [
            'beasiswa_id' => 'jenis beasiswa',
        ];
        $datapost = $this->validate($request, $rules, [], $niceNames);
        $datapost['mahasiswa_id'] = session()->get("akunId");
        $retval = PendaftaranBeasiswaController::create($request, $datapost)->getData();
        return response()->json($retval);
    }

    public function create(Request $request, $datapost)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $id = null;
        try {
            DB::beginTransaction();
            $id = Pendaftar::create($datapost)->id;
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

    public function delete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            $ids = $request['id'];
            Pendaftar::where('id', $ids)->delete();
            $retval = array("status" => true, "messages" => ["berhasil terhapus"]);
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
        }
        return response()->json($retval);
    }


    public function upload(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        //cek apakah id ada atau tidak, kalau ada maka status edit dan jika tidak ada maka insert

        if ($request->hasFile('fileupload')) {
            $this->validate($request, [
                'fileupload' => ['mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024'],
            ]);

            try {
                $file = $request->file('fileupload');
                $ext = $file->getClientOriginalExtension();
                $det =   [
                    "originalName" => $file->getClientOriginalName(),
                    "size" => ceil($file->getSize() / 5000),
                    "mime" => $file->getMimeType(),
                    "ext" => $ext,
                ];
                $datapost['user_id'] = auth()->user()->id;
                $datapost['detail'] = json_encode($det);
                $datapost['is_image'] = "1";
                if (strtolower($ext) == "pdf") {
                    $datapost['is_image'] = "0";
                }
                $destinationPath = 'syarat/' . date('Y') . '/' . $request['pendaftar_id'];

                $datapost['is_file'] = "1";
                $datapost['path'] = $file->store($destinationPath);


                DB::beginTransaction();
                //simpan upload
                $id = File::create($datapost)->id;

                //simpan lampiran berita
                $datapost = [
                    'pendaftar_id' => $request['pendaftar_id'],
                    'syarat_id' => $request['syarat_id'],
                    'file_id' => $id,
                ];
                $id = Upload::create($datapost)->id;

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

    public function uploadDelete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            DB::beginTransaction();

            $cari1 = File::where("id", $request['id'])->first();
            $cari2 = Upload::where("file_id", $request['id'])->first();
            if (isset($cari1->id)) {
                $file = $cari1->source;
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
                $cari2->delete();
                $cari1->delete();
                $retval = array("status" => true, "messages" => ["hapus file berhasil dilakukan"]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        return response()->json($retval);
    }
}
