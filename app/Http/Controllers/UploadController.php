<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DataTables;

use App\Models\File;
use App\Models\FileWeb;

class UploadController extends Controller
{

    //
    public function index()
    {
        return view('admin.upload');
    }

    public function read(Request $request)
    {
        $data = FileWeb::select('id', 'tgl', 'judul', 'slug', 'view', 'deskripsi', 'aktif', 'user_id', 'file_id')
            ->with(["file", "user"]);

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif) ? "Aktif" : "Tidak Aktif";
            })
            ->editColumn('user', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('no', function ($row) {
                return '';
            })
            ->addColumn('file', function ($row) {
                $src = asset('storage') . "/" . $row->file->path;
                $retval = '<a href="' . $src . '" target="_blank">Download</a>';
                if ($row->file->is_image)
                    $retval = '<a href="' . $src . '" target="_blank"><img src="' . $src . '" width="100%"></a>';
                return $retval;
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '" data-fileid="' . $row->file_id . '">
                            <span class="material-icons">delete_forever</span>
                        </button>';
                return $btn;
            })
            ->rawColumns(['no', 'deskripsi', 'file', 'user', 'aktif', 'action', 'cek'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $rules = [
            'judul' => 'required',
            'tgl' => 'required',
            'slug' => 'required|unique:file_webs,slug',
            'fileupload' => 'required',
            'aktif' => 'required',
        ];

        $niceNames = [
            'tgl' => 'tanggal',
        ];

        $datapost = $this->validate($request, $rules, [], $niceNames);

        $resUpload = UploadController::upload($request, 'dokumen', 'jpeg,png,jpg,gif,svg,pdf')->getData();
        $datapost['deskripsi'] = $request['deskripsi'];
        unset($datapost['fileupload']);
        if ($resUpload->status) {
            try {
                $datapost['view'] = 0;
                $datapost['file_id'] = $resUpload->id;
                $datapost['user_id'] = auth()->user()->id;
                DB::beginTransaction();
                FileWeb::create($datapost);
                DB::commit();
            } catch (\Throwable $e) {
                $retval['messages'] = [$e->getMessage()];
                DB::rollBack();
            }
        }

        $retval["status"] = true;
        $retval["messages"] = ["Simpan data berhasil dilakukan"];

        return response()->json($retval);
    }

    public function delete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            DB::beginTransaction();
            $ids = $request['id'];
            $cari = FileWeb::where("id", $ids)->first();
            $file_id = $cari->file_id;

            FileWeb::whereIn('id', $ids)->delete();
            $stsUpload = UploadController::uploadDelete($file_id)->getData();
            if ($stsUpload->status) {
                $retval = array("status" => true, "messages" => ["berhasil terhapus"]);
                DB::commit();
            } else {
                DB::rollBack();
            }
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
                'fileupload' => ['mimes:' . $mime, 'max:6144'],
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
                $destinationPath = $fldr . '/' . date('Y') . '/' . date('m');
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

    public static function uploadDelete($id)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            DB::beginTransaction();
            $cari = File::where("id", $id)->first();
            if ($cari->id) {
                $file = $cari->path;
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

    public static function search(Request $request)
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $request['srchFld'] = (!$request['srchFld']) ? "id" : $request['srchFld'];
        $request['srchGrp'] = (!$request['srchGrp']) ? "where" : $request['srchGrp'];

        if ($request['srchVal']) {
            $data = FileWeb::with(["file", "user"])
                ->orderBy('tgl', 'DESC');

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
