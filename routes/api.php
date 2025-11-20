<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Slide;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Models\V2\Device;
use Illuminate\Support\Facades\Log;
use App\Notifications\JanganLupaAbsen;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AppPackageController;
use Laravel\Passport\Http\Controllers\AccessTokenController;

// Route::get('/slide/{no}', function ($no) {

//    $file = Slide::where('slide_no', $no)->where('status', 1)->first();

//    if (empty($file)) {
//        return response(array(
//            'success' => false,
//            'message' => 'File not found',
//        ), 404);
//    }

//    $path = storage_path('/app/public/slide/' . $file->filename);

//    if (!File::exists($path)) {
//        abort(404);
//    }

//    $file = File::get($path);
//    $type = File::mimeType($path);

//    $response = Response::make($file, 200);
//    $response->header("Content-Type", $type);

//    return $response;
// })->name('slide-image');

Route::get('/app-package', [AppPackageController::class, 'getList']);

Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])
    ->middleware(['throttle:5000,1']);

Route::post('/send-notif', function (Request $request) {

    $validator = Validator::make($request->all(), [
        'nip' => 'required|array|min:1',
        'title' => 'required|string|max:255',
        'body' => 'required|string|max:255'
    ]);

    if ($validator->fails()) {
        return response(array(
            'message' => 'Permintaan tidak dapat diproses.'
            // 'message' => $validator->errors()
        ), 400);
    }

    $title = $request->title;
    $body = $request->body;

    if ($request->nip[0] == '*') {
        $devices = Device::select('notification_token')
            ->where('notification_token', '!=', 'null')
            ->where('is_active', true)
            ->get();
    } else {
        $arr_nip = $request->nip;
        $devices = Device::select('notification_token')->whereIn('nip_baru', $arr_nip)
            ->where('notification_token', '!=', 'null')
            ->where('is_active', true)
            ->get();
    }

    foreach ($devices as $device) {
        try {
            $device->notify(new JanganLupaAbsen($title, $body));
        } catch (Exception $e) {
            Log::info($e);
        }
    }

    return response(array(
        'message' => 'OK'
    ), 200);
});
