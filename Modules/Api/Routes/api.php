<?php

use Modules\Api\Http\Controllers\ApiV2Controller;
use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\ApiDevController;
use Modules\Api\Http\Controllers\ApiLayananController;
use Illuminate\Support\Facades\Hash;
use Modules\Api\Http\Controllers\ApiPermissionController;
use Modules\Api\Http\Controllers\ApiSuratController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1')->group(function () {
    Route::post('/login', [ApiV2Controller::class, 'login']);
    Route::post('/login-v1', [ApiV2Controller::class, 'login_v2']);
    Route::post('/logout', [ApiV2Controller::class, 'logout']);
    Route::get('/master-organisasi', [ApiV2Controller::class, 'masterOrganisasi']);
    Route::post('/master-jabatan', [ApiV2Controller::class, 'masterJabatan']);
    Route::post('/asn-per-organisasi', [ApiV2Controller::class, 'asnPerOrganisasi']);
    Route::get('/profile-asn/{pns_id}', [ApiV2Controller::class, 'profileAsn']);
    Route::get('/semua-asn', [ApiV2Controller::class, 'semuaAsn']);
    Route::get('/struktur-organisasi/{unor_induk_id}', [ApiV2Controller::class, 'strukturOrganisasi']);
    Route::get('/asn', [ApiV2Controller::class, 'asn']);
    Route::get('/master-puskesmas', [ApiV2Controller::class, 'masterPuskemas']);
    Route::get('/master-sekolah', [ApiV2Controller::class, 'masterSekolah']);
    Route::get('/master-kecamatan', [ApiV2Controller::class, 'masterKecamatan']);
    Route::get('/data-kelurahan/{id_kecamatan}', [ApiV2Controller::class, 'dataKelurahan']);
    Route::get('/riwayat-jabatan/{nip_baru}', [ApiV2Controller::class, 'riwayatJabatan']);
    Route::get('/verify-token/{nip_baru}', [ApiV2Controller::class, 'verifyToken'])->middleware('auth:api')->name('verify.token');
    Route::post('/refresh-token/{nip_baru}', [ApiV2Controller::class, 'refreshToken'])->name('refresh.token');
    Route::post('/update-password', [ApiV2Controller::class, 'updatePassword'])->name('update.password');
    Route::post('/save-slide', [ApiV2Controller::class, 'saveSlide'])->name('save.slide');
    Route::get('/get-access-rights/{jabatan_id}', [ApiV2Controller::class, 'getAccessRights'])->name('getAccessRight');
    Route::get('/get-struktural/{nip}', [ApiV2Controller::class, 'getStruktural'])->name('getStruktural');
    Route::get('/cek-hukdis/{nip}', [ApiV2Controller::class, 'cekHukdis'])->name('cekHukdis');
    Route::get('/search-jabatan-fungsional', [ApiV2Controller::class, 'searchJabatanFungsional'])->name('search-jabatan-fungsional');
    Route::get('/get-kepala-opd', [ApiV2Controller::class, 'getKepalaOpd'])->name('getKepalaOpd');
    Route::get('/get-list-berita', [ApiV2Controller::class, 'getListBerita'])->name('getListBerita');
    Route::get('search-pegawai', [ApiV2Controller::class, 'searchPegawai'])->middleware('auth:api');
    Route::post('tpp-pegawai', [ApiV2Controller::class, 'tppPegawai'])->middleware('auth:api');
    
    //surat
    Route::get('surat/list', [ApiSuratController::class, 'index'])->middleware(['auth:api']);
    Route::get('surat/dashboard/list', [ApiSuratController::class, 'dashboardList']);
    Route::get('surat/dashboard/matrix', [ApiSuratController::class, 'dashboardMatrix']);
    Route::post('surat/store', [ApiSuratController::class, 'store'])->middleware('auth:api');
    Route::get('surat/disposisi/berikutnya/{id}', [ApiSuratController::class, 'disposisiBerikutnya'])->middleware('auth:api');
    Route::post('surat/disposisi/store', [ApiSuratController::class, 'storeDisposisi'])->middleware('auth:api');
    Route::get('surat/cek/{kode_surat}', [ApiSuratController::class, 'cekSurat']);

    Route::get('permission/get', [ApiPermissionController::class, 'get'])->middleware('auth:api');
    Route::get('test-api', [ApiV2Controller::class, 'testApi'])->middleware('auth:api');
    Route::get('data-asisten', [ApiV2Controller::class, 'getDataAsisten']);
    Route::prefix('/layanan')->group(function () {
        Route::get('/periode-layanan/{kode_layanan}', [ApiLayananController::class, 'cekPeriodeLayanan'])->name('periode-layanan');
        Route::get('/jenis-layanan/{kode_layanan}', [ApiLayananController::class, 'getJenisLayanan'])->name('jenis-layanan');
        Route::get('/berkas-layanan/{id_jenis_layanan}', [ApiLayananController::class, 'getBerkasLayanan'])->name('berkas-layanan');
        Route::get('/draft-layanan/{nip}/{id_jenis_layanan}', [ApiLayananController::class, 'getDraftLayanan'])->name('draft-layanan');
        Route::post('/simpan-draft-berkas', [ApiLayananController::class, 'simpanDraftBerkas'])->name('simpan-draft-berkas');
        Route::post('/kirim-layanan', [ApiLayananController::class, 'kirimLayanan'])->name('kirim-layanan');
        Route::get('/hapus-berkas/{id_draft}', [ApiLayananController::class, 'hapusDraft'])->name('hapus-draft');
        Route::post('/list-trans', [ApiLayananController::class, 'listTransLayanan'])->name('list-trans');
        Route::post('/list-proses', [ApiLayananController::class, 'listProsesLayanan'])->name('list-proses');
        Route::post('/detail-proses', [ApiLayananController::class, 'detailProsesLayanan'])->name('detail-proses');
        Route::post('/detail-trans', [ApiLayananController::class, 'detailTransLayanan'])->name('detail-trans');
        Route::post('/cek-akses', [ApiLayananController::class, 'cekAksesLayanan'])->name('cek-akses');
        Route::post('/update-proses', [ApiLayananController::class, 'updateProsesLayanan'])->name('update-proses');
    });
});

Route::prefix('/development')->group(function () {
    Route::post('/login', [ApiDevController::class, 'login']);
    Route::post('/logout', [ApiDevController::class, 'logout']);
});

// Route::get('/hash', function(){
//     return Hash::make('Userdev@123');
// });

// Route::get('/hash', function(){
//     return Hash::make('P4dAn617aN!1');
// });

// Route::get('/hash1', function(){
//     return Hash::make('P4dAn617aN!2');
// });

// Route::get('/hash2', function(){
//     return Hash::make('P4dAn617aN!3');
// });