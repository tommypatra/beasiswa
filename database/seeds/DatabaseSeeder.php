<?php

use Illuminate\Database\Seeder;

use App\Models\Bagian;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\Ruang;
use App\Models\Jenis;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Kategori;
use App\Models\Berita;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //nilai default fakultas
        $dtdef = [
            "TARBIYAH DAN ILMU KEGURUAH", "SYARIAH", "USHULUDDIN ADAB DAN DAKWAH",
            "PASCASARJANA", "EKONOMI DAN BISNIS ISLAM"
        ];
        foreach ($dtdef as $dt) {
            Fakultas::create([
                'fakultas' => $dt,
                'aktif' => "1",
            ]);
        }

        //nilai default prodi
        $dtdef = [
            array("fakultas_id" => 1, "prodi" => "Pendidikan Agama Islam"),
            array("fakultas_id" => 1, "prodi" => "Pendidikan Bahasa Arab"),
            array("fakultas_id" => 1, "prodi" => "Manajemen Pendidikan Islam"),
            array("fakultas_id" => 1, "prodi" => "Pendidikan Islam Anak Usia Dini"),
            array("fakultas_id" => 1, "prodi" => "Pendidikan Guru Madrasah Ibtidaiyah"),
            array("fakultas_id" => 1, "prodi" => "Tadris Bahasa Inggris"),
            array("fakultas_id" => 1, "prodi" => "Tadris IPA"),
            array("fakultas_id" => 1, "prodi" => "Tadris Matematika"),
            array("fakultas_id" => 1, "prodi" => "Tadris Fisika"),
            array("fakultas_id" => 1, "prodi" => "Tadris Biologi"),
            array("fakultas_id" => 2, "prodi" => "Hukum Keluarga Islam (Ahwal Syakhshiyyah)"),
            array("fakultas_id" => 2, "prodi" => "Hukum Ekonomi Syariah (Mua'malah)"),
            array("fakultas_id" => 2, "prodi" => "Hukum Tatanegara (Siyasah Syar'iyyah)"),
            array("fakultas_id" => 3, "prodi" => "Komunikasi dan Penyiaran Islam"),
            array("fakultas_id" => 3, "prodi" => "Bimbingan Penyuluhan Islam"),
            array("fakultas_id" => 3, "prodi" => "Manajemen Dakwah"),
            array("fakultas_id" => 3, "prodi" => "Ilmu Al-Qur'an dan Tafsir"),
            array("fakultas_id" => 4, "prodi" => "Pendidikan Agama Islam"),
            array("fakultas_id" => 4, "prodi" => "Manajemen Pendidikan Islam"),
            array("fakultas_id" => 4, "prodi" => "Hukum Keluarga Islam (Ahwal Syakhshiyyah)"),
            array("fakultas_id" => 4, "prodi" => "Ekonomi Syariah"),
            array("fakultas_id" => 5, "prodi" => "Ekononi Syariah"),
            array("fakultas_id" => 5, "prodi" => "Perbankan Syariah"),
            array("fakultas_id" => 5, "prodi" => "Manajemen Bisnis Islam"),
        ];
        foreach ($dtdef as $dt) {
            Prodi::create([
                'prodi' => $dt['prodi'],
                'fakultas_id' => $dt['fakultas_id'],
                'aktif' => "1",
            ]);
        }

        //nilai default ruang
        $dtdef = [
            "Admin", "Pegawai", "Mahasiswa",
        ];
        foreach ($dtdef as $dt) {
            Bagian::create([
                'bagian' => $dt,
                'aktif' => "1",
            ]);
        }

        //untuk user admin
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@thisapp.com', //email login
            'password' => bcrypt('00000000'), // password default login admin
            'kel' => 'L',
            'tempatlahir' => 'Kendari',
            'tanggallahir' => date('Y-m-d'),
            'alamat' => 'BTN Kendari',
            'nohp' => '0852001019876',
            'aktif' => '1',
        ]);

        //untuk pegawai admin
        Admin::create([
            'user_id' => '1',
            'bagian_id' => '1',
            'aktif' => '1',
        ]);

        Pegawai::create([
            'no_pegawai' => '20010101001',
            'user_id' => '1',
            'bagian_id' => '2',
            'aktif' => '1',
        ]);

        //untuk user pj
        User::create([
            'nama' => 'Al Fath',
            'email' => 'alfath@thisapp.com', //email login
            'password' => bcrypt('00000000'), // password default login admin
            'kel' => 'L',
            'tempatlahir' => 'Kendari',
            'tanggallahir' => date('Y-m-d'),
            'alamat' => 'BTN Rizky',
            'nohp' => '0852901010077',
            'aktif' => '1',
        ]);

        //untuk pegawai 
        Pegawai::create([
            'no_pegawai' => '20010202002',
            'user_id' => '2',
            'bagian_id' => '2',
            'aktif' => '1',
        ]);

        //untuk user
        User::create([
            'nama' => 'Aleesya Salsabila Irawan',
            'email' => 'eca@thisapp.com', //email login
            'password' => bcrypt('00000000'), // password default login admin
            'kel' => 'L',
            'tempatlahir' => 'Kendari',
            'tanggallahir' => date('Y-m-d'),
            'alamat' => 'BTN Ranomeeto',
            'nohp' => '0852921017777',
            'aktif' => '1',
        ]);

        //untuk mahasiswa
        Mahasiswa::create([
            'nim' => '03052361',
            'prodi' => 'Pendidikan Agama Islam',
            'fakultas' => 'Tarbiyah dan Ilmu Keguruan',
            'user_id' => '3',
            'prodi_id' => '1',
            'bagian_id' => '3',
            'aktif' => '1',
        ]);

        //nilai default ruang
        $dtdef = [
            "Lab. Komputer 1", "Lab. Komputer 2", "Lab. Komputer Bahasa 1",
            "Auditorium", "Aula Perpustakaan",
        ];
        foreach ($dtdef as $dt) {
            Ruang::create([
                'ruang' => $dt,
                'aktif' => "1",
            ]);
        }

        //nilai default jenis
        $dtdef = [
            "BIDIKMISI", "KIP", "PRESTASI",
            "BANK INDONESIA", "YBM PLN", "TAHFIDZ", "PEMDA. BOMBANA",
            "PEMDA. WAKATOBI",
        ];
        foreach ($dtdef as $dt) {
            Jenis::create([
                'jenis' => $dt,
                'aktif' => "1",
            ]);
        }

        //nilai default kateogri
        $dtdef = [
            [
                "kategori" => "Profil",
                "profil" => "1",
            ],
            [
                "kategori" => "Berita",
                "profil" => "0",
            ],
            [
                "kategori" => "Pengumuman",
                "profil" => "0",
            ],
            [
                "kategori" => "Informasi Penting",
                "profil" => "0",
            ],
        ];
        foreach ($dtdef as $dt) {
            Kategori::create([
                'kategori' => $dt['kategori'],
                'profil' => $dt['profil'],
                'user_id' => 1,
                'aktif' => "1",
            ]);
        }
        //nilai default kateogri
        $dtdef = [
            [
                "judul" => "Struktur Organisasi",
                "slug" => "profil-struktur-organisasi",
                "konten" => "<p>Struktur Organisasi</p>",
            ],
            [
                "judul" => "Visi Misi",
                "slug" => "profil-visi-misi",
                "konten" => "<p>Visi Misi</p>",
            ],
            [
                "judul" => "Tentang Kami",
                "slug" => "profil-tentang-kami",
                "konten" => "<p>Tentang Kami</p>",
            ],
            [
                "judul" => "Sejarah Singkat",
                "slug" => "profil-sejarah",
                "konten" => "<p>Sejarah</p>",
            ],
        ];
        foreach ($dtdef as $dt) {
            Berita::create([
                'judul' => $dt['judul'],
                'slug' => $dt['slug'],
                'konten' => $dt['konten'],
                'aktif' => "1",
                'user_id' => 1,
                'tgl' => date("Y-m-d"),
                'kategori_id' => 1,
            ]);
        }
    }
}
