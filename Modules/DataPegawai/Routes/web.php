<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('data-pegawai')->middleware('authorized')->group(function() {
    Route::get('/', 'DataPegawaiController@index')->name('data-pegawai-index')->middleware('auth');
    Route::post('/upload','DataPegawaiController@upload')->name('data-pegawai-upload')->middleware('auth');
    Route::get('/datatables','DataPegawaiController@datatables')->name('data-pegawai-datatables');
    Route::get('/data','DataPegawaiController@datatables_2')->name('data-pegawai-data');
    Route::get('/peremajaan','DataPegawaiController@indexPeremajaan')->name('data-pegawai-peremajaan');
});
