<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RwDiklatControler;
use App\Http\Controllers\RwJabatanController;
use App\Http\Livewire\AturSandi;
use App\Http\Livewire\Cpns;
use App\Http\Livewire\DataPegawai;
use App\Http\Livewire\DataPegawaiDataUtama;
use App\Http\Livewire\DataPegawaiDiklat;
use App\Http\Livewire\DataPegawaiHukdis;
use App\Http\Livewire\DataPegawaiJabatan;
use App\Http\Livewire\DataUtama as LivewireDataUtama;
use App\Http\Livewire\DetailUsulan;
use App\Http\Livewire\Kinerja;
use App\Http\Livewire\ListUsulan;
use App\Http\Livewire\Penghargaan;
use App\Http\Livewire\Perangkat;
use App\Http\Livewire\PerangkatDibekukan;
use App\Http\Livewire\RiwayatDiklat;
use App\Http\Livewire\RiwayatHukdis;
use App\Http\Livewire\RiwayatJabatan;
use App\Http\Livewire\DaftarAkunNonAsn;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\V1\DataUtama;
use App\Models\V2\Device;
use App\Notifications\JanganLupaAbsen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

//Route::get('/', function () {

// if (Auth::check()) {
//  return redirect()->route('dashboard');
//}

// return view('landing');
//})->name('landing');

Route::get('/riwayat-jabatan', RiwayatJabatan::class)->middleware(['auth', 'authorized'])->name('riwayat-jabatan');
Route::get('/riwayat-diklat', RiwayatDiklat::class)->middleware(['auth', 'authorized'])->name('riwayat-diklat');
Route::get('/kinerja', Kinerja::class)->middleware(['auth', 'authorized'])->name('kinerja');
Route::get('/riwayat-hukdis', RiwayatHukdis::class)->middleware(['auth', 'authorized'])->name('riwayat-hukdis');
Route::get('/list-usulan', ListUsulan::class)->middleware(['auth', 'authorized'])->name('list-usulan');
Route::get('/detail-usulan/{id}', DetailUsulan::class)->middleware(['auth', 'authorized'])->name('detail-usulan');
Route::get('/cpns', Cpns::class)->middleware(['auth', 'authorized'])->name('cpns');
Route::get('/penghargaan', Penghargaan::class)->middleware(['auth', 'authorized'])->name('penghargaan');
Route::get('/data-utama', LivewireDataUtama::class)->middleware(['auth', 'authorized'])->name('data-utama');
Route::get('/data-pegawai', DataPegawai::class)->middleware(['auth', 'authorized'])->name('data-pegawai');
Route::get('/jabatan/pegawai/{nip_baru}', DataPegawaiJabatan::class)->middleware(['auth', 'authorized'])->name('jabatan.pegawai');
Route::get('/diklat/pegawai/{nip_baru}', DataPegawaiDiklat::class)->middleware(['auth', 'authorized'])->name('diklat.pegawai');
Route::get('/hukuman-disiplin/pegawai/{nip_baru}', DataPegawaiHukdis::class)->middleware(['auth', 'authorized'])->name('hukdis.pegawai');
Route::get('/data-utama/pegawai/{nip_baru}', DataPegawaiDataUtama::class)->middleware(['auth', 'authorized'])->name('data-utama.pegawai');
Route::post('/usulan-riwayat-jabatan/store', [RwJabatanController::class, 'store'])->middleware(['auth', 'authorized'])->name('usulan-riwayat-jabatan.store');
Route::post('/usulan-riwayat-diklat/store', [RwDiklatControler::class, 'store'])->middleware(['auth', 'authorized'])->name('usulan-riwayat-diklat.store');
Route::post('/usulan-riwayat-hukdis/store', [RwDiklatControler::class, 'store'])->middleware(['auth', 'authorized'])->name('usulan-riwayat-hukdis.store');
Route::get('/search-pegawai', [DashboardController::class, 'searchPegawai'])->name('search-pegawai');
Route::get('perangkat', Perangkat::class)->middleware(['auth', 'authorized', 'device.management'])->name('perangkat');
Route::get('perangkat-dibekukan', PerangkatDibekukan::class)->middleware(['auth', 'authorized', 'device.management'])->name('perangkat-dibekukan');
Route::get('perangkat/atur-ulang-kata-sandi', AturSandi::class)->middleware(['auth', 'authorized', 'device.management'])->name('perangkat.atur-sandi');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'authorized'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('login-saml', function () {
    if (request()->filled('SAMLRequest')) {
        return "<?php echo view('samlidp::components.input'); ?>";
    } else {
        return "<?php echo view('samlidp::components.input'); ?>";
    }
})->middleware('saml');

Route::get('/image/{filename}', function ($filename) {
    $path = storage_path('/app/public/image/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('image');

Route::get('/berkas/usulan/{nip}/{filename}', function ($nip, $filename) {

    $nip_baru = Crypt::decrypt($nip);
    $nama_file = Crypt::decrypt($filename);

    $path = storage_path('/app/public/usulan/' . $nip_baru . '/' . $nama_file);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('berkas.usulan');

Route::get('/berkas/{nip}/{id_berkas}/{nama_berkas}', function ($nip, $id_berkas, $nama_berkas) {

    $path = storage_path('/app/public/berkas/' . $nip . '/' . $id_berkas . '/' . $nama_berkas);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('berkas-pegawai');

Route::get('/photo/{nip}', function ($nip) {

    $path = storage_path('/app/public/photo/' . $nip . '.jpg');

    if (!File::exists($path)) {
        $path = storage_path('/app/public/photo/user-person.png');
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('photo-pegawai');

Route::get('destination', function (Request $request) {

    $token  = str_replace([' ', 'Bearer'], '', $request->header('Auth'));

    list($headersB64, $payloadB64, $sig) = explode('.', $token);

    $nip = json_decode(base64_decode($payloadB64), true)['sub'];

    $dst = json_decode(base64_decode($payloadB64), true)['dst'];

    $user = User::where('username', $nip)->first();

    if (Auth::check()) {

        return redirect()->to($dst);
    } else {

        if (!empty($user)) {

            Auth::guard('web')->login($user);

            return redirect()->to($dst);
        }

        return redirect()->route('login');
    }
})->middleware('jwt');

Route::get('/unauthorized', function () {
    $data = DataUtama::select('nama')->where('nip_baru', Auth::user()->username)->first();

    $nama = explode(' ', $data->nama)[0];

    return view('auth.unauthorized', compact('nama'));
})->name('unauthorized')->middleware('auth');

Route::get('/daftar-akun/non-asn', DaftarAkunNonAsn::class)->name('daftar-akun.non-asn');

// Route::get('/data-utama', function () {

//     $authToken = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MTg4MTQ0OTIsImlhdCI6MTcxODc3MTI5MiwianRpIjoiOTFhMGZkYjQtZWZjZS00NmE2LTgzY2YtNmIzYWUwM2NiYjhiIiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6WyJpZGlzIiwiYWNjb3VudCJdLCJzdWIiOiJhYjllMjI1MC00MWZkLTQ0OWMtYjFlZi01MWE3MGI4MDQ2ODkiLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJwYWRhbmdrb3Rhd3MiLCJzZXNzaW9uX3N0YXRlIjoiNGE0MDczNzYtMzU4OC00MDI2LWIwMTktOTI3ZjI5ZWU2Y2ZiIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmtpcmltLXVzdWwtcmluY2lhbi1mb3JtYXNpIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1tb25pdG9yLXBlcmVuY2FuYWFuLWtlcGVnYXdhaWFuIiwicm9sZTpzaWFzbi1pbnN0YW5zaTprcDphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjpyZWtvbiIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpza2s6YXBwcm92YWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktb3BlcmF0b3Itc290ayIsInJvbGU6ZGFzaGJvYXJkLW9wZXJhc2lvbmFsOmluc3RhbnNpIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjp1c3VsLXJpbmNpYW4tZm9ybWFzaSIsInJvbGU6ZGlzcGFrYXRpOmluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlbWJlcmhlbnRpYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOmlwYXNuOm1vbml0b3JpbmciLCJyb2xlOnNpYXNuLWluc3RhbnNpOmtwOnBhcmFmIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW1iZXJoZW50aWFuOmFwcHJvdmFsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1wZW5ldGFwYW4tc290ayIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjpwYXJhZiIsInJvbGU6c2lhc24taW5zdGFuc2k6cHJvZmlsYXNuOnZpZXdwcm9maWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpwYXJhZiIsInJvbGU6c2lhc24taW5zdGFuc2k6YWRtaW46YWRtaW4iXX0sInJlc291cmNlX2FjY2VzcyI6eyJpZGlzIjp7InJvbGVzIjpbImFnZW5jeS1hZG1pbiJdfSwiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJlbWFpbCBwcm9maWxlIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoiVEVHVUggU1VXQU5EQSIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5OTEwNDI0MjAxNTAyMTAwMiIsImdpdmVuX25hbWUiOiJURUdVSCIsImZhbWlseV9uYW1lIjoiU1VXQU5EQSIsImVtYWlsIjoic3V3YW5kYS50ZWd1aEBnbWFpbC5jb20ifQ.Of8IxRw7VnXrp7cEzMBbFImq4V0DJQ8anRraQ0KXAqt7Sg6vuinawABdpcgKh336tAEvcecnhMn_rew-B7glANTkj_pP9qbRCXlThjTWs2VoOLz87JywgbfYN37S8QE8odfNuzb9TR22Z4K1Jzwm_i3pmaHRHwm5vpyODZfaSgj3zeS3ZBEM-GokItPR_ADxxCsGed3pvxuwM187ZCMk7lCyBQe593iJiuBgbNx7_Y6UYtFSyutGwQuey1Np4jPNnPqcyjj-Huvq_qCf0penDnQiIhaTBquz-4T6wy5GdYcb06UUkkHsSy1LiBpu3VRtSDFJKlwItVZVOo542Bh69Q';

//     $authorizationToken = 'eyJ4NXQiOiJNell4TW1Ga09HWXdNV0kwWldObU5EY3hOR1l3WW1NNFpUQTNNV0kyTkRBelpHUXpOR00wWkdSbE5qSmtPREZrWkRSaU9URmtNV0ZoTXpVMlpHVmxOZyIsImtpZCI6Ik16WXhNbUZrT0dZd01XSTBaV05tTkRjeE5HWXdZbU00WlRBM01XSTJOREF6WkdRek5HTTBaR1JsTmpKa09ERmtaRFJpT1RGa01XRmhNelUyWkdWbE5nX1JTMjU2IiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiIxOTkxMDQyNDIwMTUwMjEwMDIiLCJhdXQiOiJBUFBMSUNBVElPTiIsImF1ZCI6ImYwTXdrcTF1ZHNCTmthRTBhR2VWakVmdEpVRWEiLCJuYmYiOjE3MTg3NzEyOTQsImF6cCI6ImYwTXdrcTF1ZHNCTmthRTBhR2VWakVmdEpVRWEiLCJzY29wZSI6ImRlZmF1bHQiLCJpc3MiOiJodHRwczpcL1wvbG9jYWxob3N0Ojk0NDNcL29hdXRoMlwvdG9rZW4iLCJleHAiOjE3MTg3NzQ4OTQsImlhdCI6MTcxODc3MTI5NCwianRpIjoiMzkzZDI1NzgtYWExMC00YmZmLThiZGQtYTdhNWM5NmU0M2Q2In0.Eii11obfJbqi4WvpLe_5DQQzIleHuYBulWyyMSnQj-fIMpQ1P2khgi6kDKrFneuELsc8yqDyI9aysYw1otRCg66f0d3o2t0a8HmXOz34bNwmSNjAXTp30TKddAXP9qBEL2XiWXPZmbijGUB7mAYgZgkjL3N5bBjoY2A16WfsvwKByFkP6W9d6Zdf8SUsZ4e1oN2Tk64clzETdeWbh3hfSOUOjanxr5fFHhuNLQEKMiUJdUxhpni_BU9g7er4kD5_H9eWNxVEMwp1cei6o2x-1GPKdyXoAwNggq5m83FN0QdeROXce5NygIjazWwjJsTM62r-IKJeNeSQN9F9KjIPBw';

//     $http_request = Http::withHeaders([
//         'accept' => 'application/json',
//         'Auth' => 'bearer ' . $authToken,
//         'Authorization' => 'Bearer ' . $authorizationToken
//     ])->get('https://apimws.bkn.go.id:8243/apisiasn/1.0/pns/data-utama/199311212024211010');

//     return $http_request;
// });

// Route::get('/info', function(){
//     return phpinfo();
// });

Route::get('/refresh', function () {
    return json_encode(decrypt('def50200b4eb4c8eb6d7fa7528b80c14bae4bf11cb1c5267a39304c25e2a5f9206bcebdf15f487bb4968ad4486841a8417a754238bc1313174d31da355124929951df795e7d2f1dcbad090d3f468c6b92878d43f21a9bbb692c78171defc9626600b76a2e476720d49dcb1df1af7f72527afceedf4bf6b31dd3a9121a551b9daa98df8cdc52409a212089a6f814ff8ff72a017000e4ede7f95774a99649540c0176b09c1b927b5da39d5d5428c102698a3fcf8b06b0122ad2dc5b3af815e2c320e5134313e2a5648951d789c3133a92f3030139568cd464aa30af40c20388ed33f47f70a347ccba76ff43ba8f242e1342c9631d2841f8f63fd9dc640109b75889616093ae6b9c7588efbbdabe86a5038f965bb4fc57e375cdc83ef0a6cf7db109fa36e8a83156e2bfed0c8f852cbfda24686b2f3901f0d3b4a792df323481048a14979c0444f463f79d198b778d315fd2440d28b3cef434104733b95e0279fb1c10fb53836c8'));
});

Route::get('/surat/file/{uid}/{unit}/{file}', function ($uid, $unit, $file) {

    $path = '/var/www/surat_masuk/' . $uid . '/' . $unit . '/' . $file;

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('surat-file');

// Route::get('/info',function(){
//     return phpinfo();
// });

// Route::get('/server-test',function(){
//     $data = DB::table('tes-table')->select('*')->orderBy('id','desc')->first();
//     $result = null;
//     if(empty($data)) {
//         $resul = DB::table('tes-table')->insert([
//             'number' => 1
//         ]);
//     }else{
//         $result = DB::table('tes-table')->insert([
//             'number' => $data->number+1,
//         ]);
//     }
//     return $result;
// });
