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
        $data = FileWeb::select('id', 'tgl', 'judul', 'deskripsi', 'aktif', 'user_id', 'file_id')
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
                return '';
            })
            ->addColumn('cek', function ($row) {
                return "<input type='checkbox' class='cekbaris' value='" . $row->id . "'>";
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '">
                            <span class="material-icons">delete_forever</span>
                        </button>';
                return $btn;
            })
            ->rawColumns(['no', 'file', 'user', 'aktif', 'action', 'cek'])
            ->make(true);
    }

    public static function upload(Request $request)
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
                $datapost['detail'] = json_encode($det);
                $datapost['is_image'] = "1";
                if (strtolower($ext) == "pdf") {
                    $datapost['is_image'] = "0";
                }
                $destinationPath = 'uploads/' . date('Y') . '/' . date('m');
                $datapost['is_file'] = "1";
                $datapost['source'] = $file->store($destinationPath);

                DB::beginTransaction();
                $id = FileUpload::create($datapost)->id;
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

    public static function uploadDelete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            DB::beginTransaction();

            $cari = File::where("id", $request['id'])->first();

            if (isset($cari->id)) {
                $file = $cari->source;
                if (Storage::disk('public')->exists($file)) {
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
        return $retval;
    }
}
