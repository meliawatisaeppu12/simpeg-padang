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

use App\Models\Simpeg\DataAsn;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::prefix('api')->group(function() {
    Route::get('/', 'ApiController@index');
});

// Route::get('insert-user', function(){
//     $username = User::all()->pluck('username');
//     $password = Hash::make('1234Padang');
//     $data_asn = DataAsn::wherenotIn('NIP_BARU',$username)->get()->map(function($val) use ($password) {
//         return array(
//             'username' => $val['NIP_BARU'],
//             'password' => $password
//         );
//     })->values();

//     $status = User::insert($data_asn->toArray());

//     return $status;
// });
