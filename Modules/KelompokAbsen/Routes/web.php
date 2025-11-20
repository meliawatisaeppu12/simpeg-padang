<?php

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

Route::middleware(['auth','authorized'])->prefix('kelompok-absen')->group(function() {
    Route::get('/', 'KelompokAbsenController@index')->name('kelompokabsen.index');
    Route::post('/cek-asn','KelompokAbsenController@cekAsn')->name('kelompokabsen.cekasn');
    Route::post('/update-kelompok-absen','KelompokAbsenController@updateKelAbsen')->name('kelompokabsen.update');
});