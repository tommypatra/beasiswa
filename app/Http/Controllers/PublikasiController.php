<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use DataTables;
use App\Models\Berita;
use App\Models\Kategori;
use App\Models\FileBerita;
use App\Models\File;

class PublikasiController extends Controller
{
    //
    public function index()
    {
        return view('admin.publikasi');
    }

    public function init()
    {
        $retval = array("status" => false, "messages" => ["tidak ditemukan"], "data" => []);
        $kategori = Kategori::where("aktif", "1")->get();
        if ($kategori->count() > 0) {
            $retval['status'] = true;
            $retval['messages'] = ["data ditemukan"];
            $retval['data']['kategori'] = $kategori;
        }
        return response()->json($retval);
    }

    public function read(Request $request)
    {
        $data = Berita::select('id', 'tgl', 'judul', 'slug', 'konten', 'aktif', 'user_id', 'created_at', 'kategori_id')
            ->with(["fileBerita.file", "kategori", "user"]);

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('aktif', function ($row) {
                return ($row->aktif) ? "Aktif" : "Tidak Aktif";
            })
            ->editColumn('kategori', function ($row) {
                return $row->kategori->kategori;
            })
            ->editColumn('user', function ($row) {
                return $row->user->nama;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->toDateTimeString();
            })
            ->addColumn('no', function ($row) {
                return '';
            })
            ->addColumn('kontenWeb', function ($row) {
                $konten = strip_tags($row->konten);
                $judul = $row->judul;
                $penulis = $row->user->nama;
                $kategori = $row->kategori->kategori;

                $retval = '<h6>' . $judul . '</h6>';
                $retval .= '<div style="font-size:10px;">Kategori : ' . $kategori . '</div>';
                $retval .= '<div style="font-size:10px;">Penulis : ' . $penulis . '</div>';
                $retval .= '<p>' . substr($judul, 0, 50) . '</p>';
                // untuk form upload
                $retval .= '<form id="fupload' . $row->id . '"><input style="font-size:10px;" class="fileupload" data-beritaid="' . $row->id . '" type="file" nama="fileupload"></form>';
                if (count($row->fileBerita) > 0) {
                    $retval .= '<ul>';
                    foreach ($row->fileBerita as $fb) {
                        $detUpload = $fb->file;

                        $file = json_decode($detUpload->detail);
                        $url = asset('storage') . '/' . $detUpload->path;
                        $retval .= '<li>';
                        $retval .= '<a href="' . $url . '" target="_blank">' . $file->originalName . '</a> ';
                        $retval .= '<a href="javascript:;" class="btn-hapus-upload" data-id="' . $detUpload->id . '"><span class="material-icons">delete_forever</span></a>';
                        $retval .= '</li>';
                    }
                    $retval .= '</ul>';
                }
                return $retval;
            })
            // ->addColumn('fileDet', function ($row) {
            //     $retval = '<form id="fupload' . $row->id . '"><input style="font-size:10px;" class="fileupload" data-beritaid="' . $row->id . '" type="file" nama="fileupload"></form>';
            //     if (count($row->fileBerita) > 0) {
            //         $retval .= '<ul>';
            //         foreach ($row->fileBerita as $fb) {
            //             $detUpload = $fb->file;

            //             $file = json_decode($detUpload->detail);
            //             $url = asset('storage') . '/' . $detUpload->path;
            //             $retval .= '<li>';
            //             $retval .= '<a href="' . $url . '" target="_blank">' . $file->originalName . '</a> ';
            //             $retval .= '<a href="javascript:;" class="btn-hapus-upload" data-id="' . $detUpload->id . '"><span class="material-icons">delete_forever</span></a>';
            //             $retval .= '</li>';
            //         }
            //         $retval .= '</ul>';
            //     }
            //     return $retval;
            // })

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
            ->rawColumns(['no', 'kontenWeb', 'kategori', 'user', 'aktif', 'action', 'cek'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $retval = array("status" => false, "insert" => true, "messages" => ["gagal, hubungi admin"]);
        $id = null;
        $insert = true;
        if ($request['id']) {
            $insert = false;
        }

        $rules = [
            'judul' => 'required',
            'slug' => 'required|unique:beritas,slug',
            'tgl' => 'required|date',
            'kategori_id' => 'required',
            'konten' => 'required',
            'aktif' => 'required',
        ];

        if (!$insert)
            $rules['slug'] = 'required';

        $niceNames = [
            'tgl' => 'tanggal publikasi',
            'kategori_id' => 'kategori publikasi',
        ];

        $datapost = $this->validate($request, $rules, [], $niceNames);

        $datapost['user_id'] = auth()->user()->id;
        $retval['insert'] = $insert;
        try {
            DB::beginTransaction();
            if ($insert) {
                $id = Berita::create($datapost)->id;
            } else {
                $id = $request['id'];
                $cari = Berita::where("id", $request['id'])->first();
                $cari->update($datapost);
            }
            $retval['id'] = $id;
            $retval["status"] = true;
            $retval["messages"] = ["Simpan data berhasil dilakukan"];
            DB::commit();
        } catch (\Throwable $e) {
            $retval['messages'] = [$e->getMessage()];
            DB::rollBack();
        }
        return response()->json($retval);
    }

    public function delete(Request $request)
    {
        $retval = array("status" => false, "messages" => ["maaf, gagal dilakukan"]);
        try {
            $ids = $request['id'];
            Berita::whereIn('id', $ids)->delete();
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
            $data = Berita::select('id', 'judul', 'tgl', 'slug', 'konten', 'aktif', 'user_id', 'created_at', 'kategori_id')
                ->with(["fileBerita.file", "kategori", "user"]);
            if ($request['srchGrp'] == 'like')
                $data->where($request['srchFld'], 'like', '%' . $request['srchVal'] . '%');
            else
                $data->where($request['srchFld'], $request['srchVal']);

            if ($data->count() > 0)
                $retval = array("status" => true, "messages" => ["data ditemukan"], "data" => $data->get());
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
                $destinationPath = 'konten/' . date('Y') . '/' . date('m');

                $datapost['is_file'] = "1";
                $datapost['path'] = $file->store($destinationPath);


                DB::beginTransaction();
                //simpan upload
                $id = File::create($datapost)->id;

                //simpan lampiran berita
                $datapost = [
                    'berita_id' => $request['berita_id'],
                    'file_id' => $id,
                ];
                $id = FileBerita::create($datapost)->id;

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
            $cari2 = FileBerita::where("file_id", $request['id'])->first();
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
