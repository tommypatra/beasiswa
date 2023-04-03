<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\SyaratController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\RuangBeasiswaController;
use App\Http\Controllers\PendaftaranBeasiswaController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('landing.web');
})->name('utama');

Route::group(['middleware' => 'guest'], function () {

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/ceklogin', [LoginController::class, 'authenticate'])->name('ceklogin');

    Route::post('/mendaftar', [PendaftaranController::class, 'mendaftar'])->name('mendaftar');

    //login google
    Route::get('/auth/redirect', [GoogleController::class, 'redirect'])->name('auth');
    Route::get('/auth/callback', [GoogleController::class, 'callback']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


    Route::middleware(['allow.header'])->group(function () {
        Route::get('/set-akses/{id}/{akunId}', [LoginController::class, 'setAkses'])->name('set-akses');
        Route::post('/admin-search', [AdminController::class, 'search'])->name('admin-search');
        Route::post('/mahasiswa-search', [MahasiswaController::class, 'search'])->name('mahasiswa-search');

        //profil user
        Route::get('/user-profil', [UserController::class, 'profil'])->name('user-profil');
        Route::post('/user-init', [UserController::class, 'myProfil'])->name('user-init');
        Route::post('/user-update', [UserController::class, 'update'])->name('user-update');
        Route::post('/user-cekpassword', [UserController::class, 'cekPassword'])->name('user-cekpassword');
        Route::post('/user-update-password', [UserController::class, 'updatePassword'])->name('user-update-password');
        Route::post('/user-label-akses', [UserController::class, 'labelAkses'])->name('user-label-akses');

        //pendaftaran hak akses
        Route::get('/pendaftaranakses', [PendaftarController::class, 'index'])->name('pendaftaran-akses');
        Route::post('/pendaftaran-init', [PendaftarController::class, 'init'])->name('pendaftaran-init');
        Route::post('/pendaftaranadmin-create', [PendaftarController::class, 'adminCreate'])->name('pendaftaran-admin-create');
        Route::post('/pendaftaranmahasiswa-create', [PendaftarController::class, 'mahasiswaCreate'])->name('pendaftaran-mahasiswa-create');
        Route::post('/pendaftaranpegawai-create', [PendaftarController::class, 'pegawaiCreate'])->name('pendaftaran-pegawai-create');
        Route::post('/pendaftaran-delete-upload', [PendaftarController::class, 'deleteUpload'])->name('pendaftaran-delete-upload');

        //upload
        Route::get('/upload', [UploadController::class, 'index'])->name('upload');
        Route::post('/upload-read', [UploadController::class, 'read'])->name('upload-read');
        Route::post('/upload-create', [UploadController::class, 'create'])->name('upload-create');
        Route::post('/upload-delete', [UploadController::class, 'delete'])->name('upload-delete');

        Route::middleware(['akses'])->group(function () {
            //pendaftaran-beasiswa
            Route::get('/pendaftaranbeasiswa', [PendaftaranBeasiswaController::class, 'index'])->name('ruang-ujian');
            Route::any('/pendaftaranbeasiswa-init', [PendaftaranBeasiswaController::class, 'init'])->name('ruang-ujian-init');
            Route::post('/pendaftaranbeasiswa-read', [PendaftaranBeasiswaController::class, 'read'])->name('ruang-ujian-read');
            Route::post('/pendaftaranbeasiswa-save', [PendaftaranBeasiswaController::class, 'save'])->name('ruang-ujian-save');
            Route::post('/pendaftaranbeasiswa-delete', [PendaftaranBeasiswaController::class, 'delete'])->name('ruang-ujian-delete');
            Route::post('/pendaftaranbeasiswa-search', [PendaftaranBeasiswaController::class, 'search'])->name('ruang-ujian-search');

            //ruang-ujian
            Route::get('/ruangujian', [RuangBeasiswaController::class, 'index'])->name('ruang-ujian');
            Route::post('/ruangujian-init', [RuangBeasiswaController::class, 'init'])->name('ruang-ujian-init');
            Route::post('/ruangujian-read', [RuangBeasiswaController::class, 'read'])->name('ruang-ujian-read');
            Route::post('/ruangujian-save', [RuangBeasiswaController::class, 'save'])->name('ruang-ujian-save');
            Route::post('/ruangujian-delete', [RuangBeasiswaController::class, 'delete'])->name('ruang-ujian-delete');
            Route::post('/ruangujian-delete-pegawai', [RuangBeasiswaController::class, 'deletePegawai'])->name('ruang-ujian-delete-pegawai');
            Route::post('/ruangujian-search', [RuangBeasiswaController::class, 'search'])->name('ruang-ujian-search');

            //ujian
            Route::get('/ujian', [UjianController::class, 'index'])->name('ujian');
            Route::post('/ujian-init', [UjianController::class, 'init'])->name('ujian-init');
            Route::post('/ujian-read', [UjianController::class, 'read'])->name('ujian-read');
            Route::post('/ujian-save', [UjianController::class, 'save'])->name('ujian-save');
            Route::post('/ujian-delete', [UjianController::class, 'delete'])->name('ujian-delete');
            Route::post('/ujian-search', [UjianController::class, 'search'])->name('ujian-search');

            //syarat
            Route::get('/syarat', [SyaratController::class, 'index'])->name('syarat');
            Route::post('/syarat-init', [SyaratController::class, 'init'])->name('syarat-init');
            Route::post('/syarat-read', [SyaratController::class, 'read'])->name('syarat-read');
            Route::post('/syarat-save', [SyaratController::class, 'save'])->name('syarat-save');
            Route::post('/syarat-delete', [SyaratController::class, 'delete'])->name('syarat-delete');
            Route::post('/syarat-search', [SyaratController::class, 'search'])->name('syarat-search');

            //beasiswa
            Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa');
            Route::post('/beasiswa-init', [BeasiswaController::class, 'init'])->name('beasiswa-init');
            Route::post('/beasiswa-read', [BeasiswaController::class, 'read'])->name('beasiswa-read');
            Route::post('/beasiswa-save', [BeasiswaController::class, 'save'])->name('beasiswa-save');
            Route::post('/beasiswa-delete', [BeasiswaController::class, 'delete'])->name('beasiswa-delete');
            //bisa semua user
            Route::post('/beasiswa-search', [BeasiswaController::class, 'search'])->name('beasiswa-search');

            //publikasi web
            Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi');
            Route::post('/publikasi-init', [PublikasiController::class, 'init'])->name('publikasi-init');
            Route::post('/publikasi-read', [PublikasiController::class, 'read'])->name('publikasi-read');
            Route::post('/publikasi-create', [PublikasiController::class, 'create'])->name('publikasi-create');
            Route::post('/publikasi-delete', [PublikasiController::class, 'delete'])->name('publikasi-delete');
            Route::post('/publikasi-search', [PublikasiController::class, 'search'])->name('publikasi-search');
            Route::post('/publikasi-upload', [PublikasiController::class, 'upload'])->name('publikasi-upload');
            Route::post('/publikasi-upload-delete', [PublikasiController::class, 'uploadDelete'])->name('publikasi-upload-delete');
        });
    });
});
