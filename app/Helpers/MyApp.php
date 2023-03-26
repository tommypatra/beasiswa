<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redirect;

class MyApp
{

    public static function decodeNIK($vdata = null)
    {
        $retval = [];
        //inisiasi tahun sekarang
        $thnskrng = date("Y");
        //menyiapkan temporari tahun
        $tmpthn = (int)substr($thnskrng, 0, 2);

        $retval["prov"] = substr($vdata, 0, 2);
        $retval["kab"] = substr($vdata, 2, 2);
        $retval["kec"] = substr($vdata, 4, 2);

        $tgl = (int)substr($vdata, 6, 2);
        $bln = (int)substr($vdata, 8, 2);
        $thn = (int)substr($vdata, 10, 2);

        $thnlahir = ($tmpthn . $thn);
        if ((int)$thnlahir > (int)$thnskrng) {
            $thnlahir = ($tmpthn - 1) . $thn;
        }

        $retval["kel"] = "L";
        if ($tgl > 40) {
            $tgl = $tgl - 40;
            $retval["kel"] = "P";
        }
        $retval["tgllahir"] = date("Y-m-d");
        if (checkdate($bln, $tgl, $thnlahir))
            $retval["tgllahir"] = $thnlahir . "-" . $bln . "-" . $tgl;
        //echo $retval["tgllahir"];
        //die;
        return $retval;
    }

    public static function allowheader($content_type = "application/json")
    {
        $retval = array("status" => false, "pesan" => ["tidak diperbolehkan"]);
        $allow = [
            '127.0.0.1',
            'beasiswa.iainkendari.ac.id',
        ];
        $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "https://beasiswa.iainkendari.ac.id";
        $web_origin = parse_url($http_origin);

        Header("Access-Control-Allow-Origin: " . $http_origin);
        Header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Credentials: true");
        Header("Access-Control-Allow-Methods: GET, POST");
        //header("Content-Type: " . $content_type . "; charset=utf-8");

        if (in_array($web_origin['host'], $allow)) {
            $retval = array("status" => true, "pesan" => ["diperbolehkan"]);
        }
        return $retval;
    }

    public static function waktu_lalu($timestamp = null)
    {
        $waktu = "";
        if ($timestamp) {
            $phpdate = strtotime($timestamp);
            $mysqldate = date('Y-m-d H:i:s', $phpdate);

            $selisih = time() - strtotime($mysqldate);
            $detik = $selisih;
            $menit = round($selisih / 60);
            $jam = round($selisih / 3600);
            $hari = round($selisih / 86400);
            $minggu = round($selisih / 604800);
            $bulan = round($selisih / 2419200);
            $tahun = round($selisih / 29030400);
            if ($detik <= 60) {
                $waktu = $detik . ' detik lalu';
            } else if ($menit <= 60) {
                $waktu = $menit . ' menit lalu';
            } else if ($jam <= 24) {
                $waktu = $jam . ' jam lalu';
            } else if ($hari <= 7) {
                $waktu = $hari . ' hari lalu';
            } else if ($minggu <= 4) {
                $waktu = $minggu . ' minggu lalu';
            } else if ($bulan <= 12) {
                $waktu = $bulan . ' bulan lalu';
            } else {
                $waktu = $tahun . ' tahun lalu';
            }
        }
        return $waktu;
    }

    public static function generateToken($length = 32)
    {
        $randomString = "";
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString .= date("s") . $characters[rand(0, $charactersLength - 1)];
        $randomString .= date("m") . $characters[rand(0, $charactersLength - 1)];
        $randomString .= date("y") . $characters[rand(0, $charactersLength - 1)];
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $randomString .= date("i") . $characters[rand(0, $charactersLength - 1)];
        $randomString .= date("H") . $characters[rand(0, $charactersLength - 1)];
        $randomString .= date("d") . $characters[rand(0, $charactersLength - 1)];
        return $randomString;
    }

    public static function hakakses($link = null)
    {
        $retval = ['c' => '0', 'r' => '0', 'u' => '0', 'd' => '0', 's' => '0'];
        $check = \App\Models\Akses::select("akses.c", "akses.r", "akses.u", "akses.d", "akses.s")
            ->leftJoin('menus', function ($join) {
                $join->on('menus.id', '=', 'akses.menu_id');
            })
            ->leftJoin('grups', function ($join) {
                $join->on('grups.id', '=', 'menus.grup_id');
            })
            ->leftJoin('moduls', function ($join) {
                $join->on('moduls.id', '=', 'menus.modul_id');
            })
            ->where("moduls.link", $link)
            ->where("grups.id", session()->get("akses"))
            ->first();

        if ($check) {
            $retval = ['c' => $check->c, 'r' => $check->r, 'u' => $check->u, 'd' => $check->d, 's' => $check->s];
        }

        return $retval;
    }

    public static function detailLogin($email = null)
    {
        $dt = \App\Models\User::with(['fotoUser.file', 'admin.bagian', 'mahasiswa.bagian', 'pegawai.bagian'])->where("email", $email)->get();
        $mhs = [];
        $pegawai = [];
        $admin = [];
        $def = null;

        if (session()->has('akses')) {
            $def = [
                'akses' => session()->get('akses'),
                'akunId' => session()->get('akunId')
            ];
        }

        foreach ($dt as $dp) {
            foreach ($dp->admin as $pg) {
                $tmp = $pg->bagian;
                $admin[] = ["id" => 1, "akunid" => $pg->id, "grup" => $tmp->bagian, "noid" => "web", "aktif" => $pg->aktif];
                if (!$def && $pg->aktif == "1") {
                    $def['akses'] = $tmp->id;
                    $def['akunId'] = $pg->id;
                }
            }
            foreach ($dp->pegawai as $pg) {
                $tmp = $pg->bagian;
                $pegawai[] = ["id" => 2, "akunid" => $pg->id, "grup" => $tmp->bagian, "noid" => $pg->no_pegawai, "aktif" => $pg->aktif];
                if (!$def && $pg->aktif == "1") {
                    $def['akses'] = $tmp->id;
                    $def['akunId'] = $pg->id;
                }
            }
            foreach ($dp->mahasiswa as $pg) {
                $tmp = $pg->bagian;
                $mhs[] = ["id" => 3, "akunid" => $pg->id, "grup" => $tmp->bagian, "noid" => $pg->nim, "aktif" => $pg->aktif];
                if (!$def && $pg->aktif == "1") {
                    $def['akses'] = $tmp->id;
                    $def['akunId'] = $pg->id;
                }
            }
        }

        if (!$def || (count($mhs) < 1 && count($admin) < 1 &&  count($pegawai) < 1)) {
            $def = [
                'akses' => 0,
                'akunId' => 0
            ];
        }

        $ret = ['foto' => collect($dt[0]->fotoUser), 'admin' => collect($admin), 'mahasiswa' => collect($mhs), 'pegawai' => collect($pegawai), 'default' => $def];
        return $ret;
    }

    public static function buildTree(array $elements, $parentId = null, $id = "id", $idp = "parent_id", $cld = "children")
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element[$idp] == $parentId) {
                //echo $idp . "=" . $element[$idp];
                //die;
                $children = MyApp::buildTree($elements, $element[$id], $id, $idp);
                if ($children) {
                    $element[$cld] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public static function buildMenu($array, &$menu = "")
    {
        $menu .= '<ul>';
        foreach ($array as $item) {
            $menu .= '<li>';
            $menu .= '<a href="' . $item['modul']['link'] . '">' . $item['modul']['menu'] . '</a>';
            if (isset($item['children'])) {
                MyApp::buildMenu($item['children'], $menu);
            }
            $menu .= '</li>';
        }
        $menu .= '</ul>';
        return $menu;
    }

    public static function format_rupiah($vuang = 0, $vkoma = 0)
    {
        return number_format($vuang, $vkoma, ",", ".");
    }

    public static function extractemail($email)
    {
        $retval = array();
        preg_match("/^(.+)@([^\(\);:,<>]+\.[a-zA-Z]+)/", $email, $retval);
        return $retval;
    }

    public static function readAkses()
    {
        $hakakses = [];
        $tmpakses = session()->get("admin");
        if (count($tmpakses) > 0)
            foreach ($tmpakses as $i => $dp) {
                if ($dp['aktif'] == "1")
                    $hakakses[] = [
                        "akunid" => $dp['akunid'],
                        "id" => $dp['id'],
                        "label" => $dp['grup'] . " (" . $dp['noid'] . ")",
                    ];
            }

        $tmpakses = session()->get("pegawai");
        if (count($tmpakses) > 0)
            foreach ($tmpakses as $i => $dp) {
                //print_r($dp);
                if ($dp['aktif'] == "1")
                    $hakakses[] = [
                        "akunid" => $dp['akunid'],
                        "id" => $dp['id'],
                        "label" => $dp['grup'] . " (" . $dp['noid'] . ")",
                    ];
            }

        $tmpakses = session()->get("mahasiswa");
        if (count($tmpakses) > 0)
            foreach ($tmpakses as $i => $dp) {
                if ($dp['aktif'] == "1")
                    $hakakses[] = [
                        "akunid" => $dp['akunid'],
                        "id" => $dp['id'],
                        "label" => $dp['grup'] . " (" . $dp['noid'] . ")",
                    ];
            }

        $hakakses[] = [
            "akunid" => 0,
            "id" => 0,
            "label" => "Tamu",
        ];

        $retval['data'] = $hakakses;
        return response()->json($retval);
    }
}
