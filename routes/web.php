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

    Route::post('/admin-search', [AdminController::class, 'search'])->name('admin-search');
    Route::post('/mahasiswa-search', [MahasiswaController::class, 'search'])->name('mahasiswa-search');

    Route::middleware(['akses'])->group(function () {
        Route::get('/set-akses/{id}/{akunId}', [LoginController::class, 'setAkses'])->name('set-akses');

        //upload
        Route::get('/upload', [UploadController::class, 'index'])->name('upload');
        Route::post('/upload-read', [UploadController::class, 'read'])->name('upload-read');
        Route::post('/upload-create', [UploadController::class, 'create'])->name('upload-create');
        Route::post('/upload-delete', [UploadController::class, 'delete'])->name('upload-delete');

        //publikasi web
        Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi');
        Route::post('/publikasi-init', [PublikasiController::class, 'init'])->name('publikasi-init');
        Route::post('/publikasi-read', [PublikasiController::class, 'read'])->name('publikasi-read');
        Route::post('/publikasi-create', [PublikasiController::class, 'create'])->name('publikasi-create');
        Route::post('/publikasi-delete', [PublikasiController::class, 'delete'])->name('publikasi-delete');
        Route::post('/publikasi-search', [PublikasiController::class, 'search'])->name('publikasi-search');
        Route::post('/publikasi-upload', [PublikasiController::class, 'upload'])->name('publikasi-upload');
        Route::post('/publikasi-upload-delete', [PublikasiController::class, 'uploadDelete'])->name('publikasi-upload-delete');

        //profil user
        Route::get('/user-profil', [UserController::class, 'profil'])->name('user-profil');
        Route::post('/user-init', [UserController::class, 'myProfil'])->name('user-init');
        Route::post('/user-update', [UserController::class, 'update'])->name('user-update');
        Route::post('/user-cekpassword', [UserController::class, 'cekPassword'])->name('user-cekpassword');
        Route::post('/user-update-password', [UserController::class, 'updatePassword'])->name('user-update-password');
        Route::post('/user-label-akses', [UserController::class, 'labelAkses'])->name('user-label-akses');

        //pendaftaran hak akses
        Route::get('/pendaftaran-akses', [PendaftarController::class, 'index'])->name('pendaftaran-akses');
        Route::post('/pendaftaran-init', [PendaftarController::class, 'init'])->name('pendaftaran-init');
        Route::post('/pendaftaran-admin-create', [PendaftarController::class, 'adminCreate'])->name('pendaftaran-admin-create');
        Route::post('/pendaftaran-mahasiswa-create', [PendaftarController::class, 'mahasiswaCreate'])->name('pendaftaran-mahasiswa-create');
        Route::post('/pendaftaran-pegawai-create', [PendaftarController::class, 'pegawaiCreate'])->name('pendaftaran-pegawai-create');
        Route::post('/pendaftaran-delete-upload', [PendaftarController::class, 'deleteUpload'])->name('pendaftaran-delete-upload');
    });
});
