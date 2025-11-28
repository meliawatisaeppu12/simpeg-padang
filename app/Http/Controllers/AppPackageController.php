<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\V2\BLApp;

class AppPackageController extends Controller
{
    public function getList()
    {
        $app = BLApp::select('id','nama_package')->where('status', true)->get();

        if($app->count() == 0){
            return response(array(
                'message' => 'Data tidak ditemukan.',
            ),404);
        }

        return response(array(
            'message' => 'Data ditemukan.',
            'data' => $app,
        ),200);
    }
}
