<?php

namespace Modules\Api\Http\Controllers;

use App\Models\LogMUltiLogin;
use App\Models\V2\RefJabatanFungsional;
use App\Models\Slide;
use App\Models\V2\DataUtama;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\V2\MasterOrganisasi;
use App\Models\V2\MasterUnitOrganisasi;
use App\Models\V1\MasterJabatan;
use App\Models\User;
use App\Models\V2\ApplicationList;
use App\Models\V2\ApplicationRules;
use App\Models\V2\Berita;
use App\Models\V2\Device;
use App\Models\V2\KelompokAbsen;
use App\Models\V2\KepalaPuskesmas;
use App\Models\V2\MasterKecamatan;
use App\Models\V2\MasterKelasJabatan;
use App\Models\V2\MasterKelurahan;
use App\Models\V2\MasterPuskesmas;
use App\Models\V2\PersonalAccess;
use App\Models\V2\Plt;
use App\Models\V2\PositionAccess;
use App\Models\V2\RefJabatanFungsionalUmum;
use App\Models\V2\RefUnor;
use App\Models\V2\RiwayatGolongan;
use App\Models\V2\RiwayatHukdis;
use App\Models\V2\RiwayatJabatan;
use App\Models\V2\RiwayatPendidikan;
use App\Models\V2\UsersActivity;
use Carbon\Carbon;
use Laravel\Passport\Token;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DashboardController;
use App\Models\V2\DeviceNonAsn;
use App\Models\V2\LockedAccount;
use App\Models\V2\NonAsn;
use App\Models\V2\UnknownDevice;
use Illuminate\Support\Facades\Http;

class ApiV2Controller extends Controller
{

    public function index()
    {
        return redirect()->route('landing');
    }

    public function login(Request $request)
    {

        if (empty($request->username) || empty($request->password)) {
            return response([
                'login_success' => false,
                'message' => 'missing username/password'
            ], 400);
        }

        if (empty($request->client_id) || empty($request->client_secret)) {
            return response([
                'login_success' => false,
                'message' => 'missing client_id/client_secret'
            ], 400);
        }

        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return response([
                'login_success' => false,
                'message' => 'Invalid Login'
            ], 400);
        }

        $uuid = (string) $request->uuid;

        if (!Auth::user()->active) {
            return response([
                'login_success' => false,
                'message' => 'Akun tidak aktif.'
            ], 400);
        }

        $device = Device::select('nip_baru')
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->with(['dataUtama' => function ($query) {
                $query->select('nip_baru', 'nama');
            }])
            ->orderBy('id', 'ASC')
            ->first();

        $uuid_arr = [
            'zx8bapu3-9qo8-jy6p-4m6c-kmyeuau25l81',
            'h0mao47z-eruk-8h0l-e6vg-nxk7c82oj8jt',
            'vxi45d2n-kyt9-sbkg-pkz0-fw41ldvcdgiy'
        ];

        $is_contains = false;

        foreach ($uuid_arr as $data) {
            if (str_contains($request->uuid, $data)) {
                $is_contains = true;
            }
        }

        if (!str_contains($request->username, '199705272025041004')) {
            if (!$is_contains) {
                // if (!str_contains($request->uuid, 'h0mao47z-eruk-8h0l-e6vg-nxk7c82oj8jt') && !str_contains($request->uuid, 'vxi45d2n-kyt9-sbkg-pkz0-fw41ldvcdgiy')) {
                if (isset($device)) {
                    if ($device->nip_baru != $request->username) {

                        $log = new LogMUltiLogin();
                        $log->nip = $request->username;
                        $log->device_uuid = $request->uuid;
                        $log->save();

                        return response([
                            'login_success' => false,
                            'message' => 'Perangkat terdaftar pada akun ' . ucwords(strtolower($device->dataUtama->nama))
                        ], 400);
                    }
                }
            }
        }


        $token = $this->getTokenAndRefreshToken($request->username, $request->password, $request->client_id, $request->client_secret);

        $notification_token = $request->fcm ?? null;

        if (!empty($request->brand) && !$is_contains) {
            // if (!empty($request->brand) && ($request->uuid != 'h0mao47z-eruk-8h0l-e6vg-nxk7c82oj8jt' && !str_contains($request->uuid, 'vxi45d2n-kyt9-sbkg-pkz0-fw41ldvcdgiy'))) {

            $app_id = ApplicationList::select('id')->where('app_code', $request->app_code)->first()->id;

            $is_allowed = ApplicationRules::select('is_allowed')->where('app_id', $app_id)->where('rule_name', 'like', '%multiple%')->first()->is_allowed;

            $registered_device = Device::where('nip_baru', $request->username)
                ->where('uuid', '!=', null)
                ->orWhere('uuid', '!=', '')
                ->where('nip_baru', $request->username)
                ->get();

            $arr_uuid = $registered_device->where('is_active', true)->pluck('uuid')->toArray();

            $tokenPayload = explode('.', $token['access_token'])[1];

            if ($registered_device->count() > 0) {
                if ($is_allowed) {
                    if (in_array($request->uuid, $arr_uuid)) {
                        $device = Device::where('uuid', $request->uuid)->first();
                        $device->nip_baru = $request->username;
                        $device->app_version = $request->app_version;
                        $device->device = $request->device;
                        $device->device_brand = $request->brand;
                        $device->device_model = $request->model;
                        $device->device_id = $request->deviceId;
                        $device->device_abis = $request->supportedAbis;
                        $device->android_release = $request->release;
                        $device->android_sdkInt = $request->sdkInt;
                        $device->uuid = $request->uuid;
                        $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                        $device->is_active = true;
                        $device->ads_id = $request->ads_id;
                        $device->notification_token = $notification_token;
                        $device->save();
                    } else {
                        if ($registered_device->where('is_active', true)->count() > 9) {
                            $locked_log = LockedAccount::where('nip', $request->username)->where('is_locked', true)->first();
                            if (empty($locked_log)) {
                                $locked_log = new LockedAccount();
                                $locked_log->nip = $request->username;
                                $locked_log->device = $request->uuid;
                                $locked_log->save();

                                $device = new Device();
                                $device->nip_baru = $request->username;
                                $device->app_version = $request->app_version;
                                $device->device = $request->device;
                                $device->device_brand = $request->brand;
                                $device->device_model = $request->model;
                                $device->device_id = $request->deviceId;
                                $device->device_abis = $request->supportedAbis;
                                $device->android_release = $request->release;
                                $device->android_sdkInt = $request->sdkInt;
                                $device->uuid = $request->uuid;
                                $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                                $device->ads_id = $request->ads_id;
                                $device->notification_token = $notification_token;
                                $device->save();
                            } else {
                                $locked_log->counted = $locked_log->counted + 1;
                                $locked_log->save();

                                $nama = DataUtama::select('nama')->where('nip_baru', $request->username)->first()->nama;

                                $device = new UnknownDevice();
                                $device->nip_baru = $request->username;
                                $device->app_version = $request->app_version;
                                $device->device = $request->device;
                                $device->device_brand = $request->brand;
                                $device->device_model = $request->model;
                                $device->device_id = $request->deviceId;
                                $device->device_abis = $request->supportedAbis;
                                $device->android_release = $request->release;
                                $device->android_sdkInt = $request->sdkInt;
                                $device->uuid = $request->uuid;
                                $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                                $device->ads_id = $request->ads_id;
                                $device->notification_token = $notification_token;
                                $device->save();

                                return response([
                                    'login_success' => false,
                                    'message' => 'Yth. ' . $nama . ', akun anda sedang dibekukan.',
                                ], 400);
                            }
                        } else {
                            $device = new Device();
                            $device->nip_baru = $request->username;
                            $device->app_version = $request->app_version;
                            $device->device = $request->device;
                            $device->device_brand = $request->brand;
                            $device->device_model = $request->model;
                            $device->device_id = $request->deviceId;
                            $device->device_abis = $request->supportedAbis;
                            $device->android_release = $request->release;
                            $device->android_sdkInt = $request->sdkInt;
                            $device->uuid = $request->uuid;
                            $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                            $device->ads_id = $request->ads_id;
                            $device->notification_token = $notification_token;
                            $device->save();
                        }
                    }
                } else {
                    if (in_array($request->uuid, $arr_uuid)) {
                        $device = Device::where('uuid', $request->uuid)->first();
                        $device->nip_baru = $request->username;
                        $device->app_version = $request->app_version;
                        $device->device = $request->device;
                        $device->device_brand = $request->brand;
                        $device->device_model = $request->model;
                        $device->device_id = $request->deviceId;
                        $device->device_abis = $request->supportedAbis;
                        $device->android_release = $request->release;
                        $device->android_sdkInt = $request->sdkInt;
                        $device->uuid = $request->uuid;
                        $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                        $device->ads_id = $request->ads_id;
                        $device->notification_token = $notification_token;
                        $device->save();
                    } else {
                        return response(array(
                            'login_success' => false,
                            'message' => "Anda sudah login pada perangkat lain.",
                        ), 400);
                    }
                }
            } else {
                $device = new Device();
                $device->nip_baru = $request->username;
                $device->app_version = $request->app_version;
                $device->device = $request->device;
                $device->device_brand = $request->brand;
                $device->device_model = $request->model;
                $device->device_id = $request->deviceId;
                $device->device_abis = $request->supportedAbis;
                $device->android_release = $request->release;
                $device->android_sdkInt = $request->sdkInt;
                $device->uuid = $request->uuid;
                $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                $device->ads_id = $request->ads_id;
                $device->notification_token = $notification_token;
                $device->save();
            }
        }

        UsersActivity::create([
            'nip' => $request->username,
            'activity' => 'sign in',
            'device' => $request->uuid,
        ]);

        $jabatan_struktural_id = Auth::user()->v2Profile->jabatan_struktural_id;

        $jabatan_fungsional_id = Auth::user()->v2Profile->jabatan_fungsional_id;

        $jabatan_fungsional_umum_id = Auth::user()->v2Profile->jabatan_fungsional_umum_id;

        $jabatan_id = !empty($jabatan_struktural_id) ? $jabatan_struktural_id : (!empty($jabatan_fungsional_id) ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);

        $unor_induk_id = KelompokAbsen::select('unor_induk_id')->where('unor_id', Auth::user()->v2Profile->unor_id)->first()->unor_induk_id;

        $unor_induk_nama = KelompokAbsen::select('unor_nama')->where('unor_id', $unor_induk_id)->first()->unor_nama;

        $response = [
            'login_success' => true,
            'message' => "Login Success",
            'user' => [
                'PNS_ID' =>  Auth::user()->v2Profile->id,
                'NIP_BARU' =>  (string)Auth::user()->v2Profile->nip_baru,
                'NIK' =>  (string)Auth::user()->v2Profile->nik,
                'NAMA' => str_replace("KORTI ", "KORTI", str_replace("AMALIA ", "AMALIA", Auth::user()->v2Profile->nama)),
                'ALAMAT' => str_replace(["   ", "  "], " ", Auth::user()->v2Profile->alamat),
                'TEMPAT_LAHIR' => Auth::user()->v2Profile->tempat_lahir,
                'TANGGAL_LAHIR' => Carbon::createFromFormat(
                    'd-m-Y',
                    Auth::user()->v2Profile->tanggal_lahir,
                    'Asia/Jakarta'
                )->format('Y-m-d'),
                'NO_HP' => Auth::user()->v2Profile->no_hp,
                'JENIS_JABATAN_ID' => Auth::user()->v2Profile->jenis_jabatan_id,
                'JABATAN_ID' => $jabatan_id,
                'JABATAN_NAMA' => str_replace("  ", " ", Auth::user()->v2Profile->jabatan_nama),
                'JENIS_KELAMIN' => Auth::user()->v2Profile->jenis_kelamin,
                'UNOR_ID' => Auth::user()->v2Profile->unor_id,
                'UNOR_NAMA' => str_replace("  ", " ", Auth::user()->v2Profile->unor_nama),
                'UNOR_INDUK_ID' => $unor_induk_id,
                'UNOR_INDUK_NAMA' => $unor_induk_nama,
            ],
        ];

        $response['token_type'] = $token['token_type'];
        $response['expires_in'] = $token['expires_in'];
        $response['expires_at'] = Carbon::parse(strtotime(Carbon::now()) + $token['expires_in'], 'Asia/Jakarta')->translatedFormat('j F Y H:i');
        $response['access_token'] = $token['access_token'];
        $response['refresh_token'] = $token['refresh_token'];

        return response($response, 200);
    }

    public function login_v2(Request $request)
    {
        Log::info($request);
        if (empty($request->username) || empty($request->password)) {
            return response([
                'login_success' => false,
                'message' => 'missing username/password'
            ], 400);
        }

        if (empty($request->client_id) || empty($request->client_secret)) {
            return response([
                'login_success' => false,
                'message' => 'missing client_id/client_secret'
            ], 400);
        }

        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return response([
                'login_success' => false,
                'message' => 'Invalid Login'
            ], 400);
        }

        $uuid = (string) $request->uuid;

        if (!Auth::user()->active) {
            return response([
                'message' => 'Akun tidak aktif.'
            ], 400);
        }

        $uuid_arr = [
            'zx8bapu3-9qo8-jy6p-4m6c-kmyeuau25l81',
            'h0mao47z-eruk-8h0l-e6vg-nxk7c82oj8jt',
            'vxi45d2n-kyt9-sbkg-pkz0-fw41ldvcdgiy',
            'gea8qm8m-9eio-mw6h-mdjd-k9fng0ahlcw5'
        ];

        $is_contains = false;

        foreach ($uuid_arr as $data) {
            if (str_contains($request->uuid, $data)) {
                $is_contains = true;
            }
        }


        if (is_numeric($request->username)) {
            $device = Device::select('nip_baru')
                ->where('uuid', $uuid)
                ->where('is_active', true)
                ->with(['dataUtama' => function ($query) {
                    $query->select('nip_baru', 'nama');
                }])
                ->orderBy('id', 'ASC')
                ->first();

            if (!str_contains($request->username, '199705272025041004')) {
                if (!$is_contains) {
                    // if (!str_contains($request->uuid, 'h0mao47z-eruk-8h0l-e6vg-nxk7c82oj8jt') && !str_contains($request->uuid, 'vxi45d2n-kyt9-sbkg-pkz0-fw41ldvcdgiy')) {
                    if (isset($device)) {
                        if ($device->nip_baru != $request->username) {

                            $log = new LogMUltiLogin();
                            $log->nip = $request->username;
                            $log->device_uuid = $request->uuid;
                            $log->save();

                            return response([
                                'login_success' => false,
                                'message' => 'Perangkat terdaftar pada akun ' . ucwords(strtolower($device->dataUtama->nama))
                            ], 400);
                        }
                    }
                }
            }


            $token = $this->getTokenAndRefreshToken($request->username, $request->password, $request->client_id, $request->client_secret);

            $notification_token = $request->fcm ?? null;

            if (!empty($request->brand) && !$is_contains) {
                // if (!empty($request->brand) && ($request->uuid != 'h0mao47z-eruk-8h0l-e6vg-nxk7c82oj8jt' && !str_contains($request->uuid, 'vxi45d2n-kyt9-sbkg-pkz0-fw41ldvcdgiy'))) {

                $app_id = ApplicationList::select('id')->where('app_code', $request->app_code)->first()->id;

                $is_allowed = ApplicationRules::select('is_allowed')->where('app_id', $app_id)->where('rule_name', 'like', '%multiple%')->first()->is_allowed;

                $registered_device = Device::where('nip_baru', $request->username)
                    ->where('uuid', '!=', null)
                    ->orWhere('uuid', '!=', '')
                    ->where('nip_baru', $request->username)
                    ->get();

                $arr_uuid = $registered_device->where('is_active', true)->pluck('uuid')->toArray();

                $tokenPayload = explode('.', $token['access_token'])[1];

                if ($registered_device->count() > 0) {
                    if ($is_allowed) {
                        if (in_array($request->uuid, $arr_uuid)) {
                            $device = Device::where('uuid', $request->uuid)->first();
                            $device->nip_baru = $request->username;
                            $device->app_version = $request->app_version;
                            $device->device = $request->device;
                            $device->device_brand = $request->brand;
                            $device->device_model = $request->model;
                            $device->device_id = $request->deviceId;
                            $device->device_abis = $request->supportedAbis;
                            $device->android_release = $request->release;
                            $device->android_sdkInt = $request->sdkInt;
                            $device->uuid = $request->uuid;
                            $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                            $device->is_active = true;
                            $device->ads_id = $request->ads_id;
                            $device->notification_token = $notification_token;
                            $device->save();
                        } else {
                            if ($registered_device->where('is_active', true)->count() > 9) {
                                $locked_log = LockedAccount::where('nip', $request->username)->where('is_locked', true)->first();
                                if (empty($locked_log)) {
                                    $locked_log = new LockedAccount();
                                    $locked_log->nip = $request->username;
                                    $locked_log->device = $request->uuid;
                                    $locked_log->save();

                                    $device = new Device();
                                    $device->nip_baru = $request->username;
                                    $device->app_version = $request->app_version;
                                    $device->device = $request->device;
                                    $device->device_brand = $request->brand;
                                    $device->device_model = $request->model;
                                    $device->device_id = $request->deviceId;
                                    $device->device_abis = $request->supportedAbis;
                                    $device->android_release = $request->release;
                                    $device->android_sdkInt = $request->sdkInt;
                                    $device->uuid = $request->uuid;
                                    $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                                    $device->ads_id = $request->ads_id;
                                    $device->notification_token = $notification_token;
                                    $device->save();
                                } else {
                                    $locked_log->counted = $locked_log->counted + 1;
                                    $locked_log->save();

                                    $nama = DataUtama::select('nama')->where('nip_baru', $request->username)->first()->nama;

                                    $device = new UnknownDevice();
                                    $device->nip_baru = $request->username;
                                    $device->app_version = $request->app_version;
                                    $device->device = $request->device;
                                    $device->device_brand = $request->brand;
                                    $device->device_model = $request->model;
                                    $device->device_id = $request->deviceId;
                                    $device->device_abis = $request->supportedAbis;
                                    $device->android_release = $request->release;
                                    $device->android_sdkInt = $request->sdkInt;
                                    $device->uuid = $request->uuid;
                                    $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                                    $device->ads_id = $request->ads_id;
                                    $device->notification_token = $notification_token;
                                    $device->save();

                                    return response([
                                        'login_success' => false,
                                        'message' => 'Yth. ' . $nama . ', akun anda sedang dibekukan.',
                                    ], 400);
                                }
                            } else {
                                $device = new Device();
                                $device->nip_baru = $request->username;
                                $device->app_version = $request->app_version;
                                $device->device = $request->device;
                                $device->device_brand = $request->brand;
                                $device->device_model = $request->model;
                                $device->device_id = $request->deviceId;
                                $device->device_abis = $request->supportedAbis;
                                $device->android_release = $request->release;
                                $device->android_sdkInt = $request->sdkInt;
                                $device->uuid = $request->uuid;
                                $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                                $device->ads_id = $request->ads_id;
                                $device->notification_token = $notification_token;
                                $device->save();
                            }
                        }
                    } else {
                        if (in_array($request->uuid, $arr_uuid)) {
                            $device = Device::where('uuid', $request->uuid)->first();
                            $device->nip_baru = $request->username;
                            $device->app_version = $request->app_version;
                            $device->device = $request->device;
                            $device->device_brand = $request->brand;
                            $device->device_model = $request->model;
                            $device->device_id = $request->deviceId;
                            $device->device_abis = $request->supportedAbis;
                            $device->android_release = $request->release;
                            $device->android_sdkInt = $request->sdkInt;
                            $device->uuid = $request->uuid;
                            $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                            $device->ads_id = $request->ads_id;
                            $device->notification_token = $notification_token;
                            $device->save();
                        } else {
                            return response(array(
                                'login_success' => false,
                                'message' => "Anda sudah login pada perangkat lain.",
                            ), 400);
                        }
                    }
                } else {
                    $device = new Device();
                    $device->nip_baru = $request->username;
                    $device->app_version = $request->app_version;
                    $device->device = $request->device;
                    $device->device_brand = $request->brand;
                    $device->device_model = $request->model;
                    $device->device_id = $request->deviceId;
                    $device->device_abis = $request->supportedAbis;
                    $device->android_release = $request->release;
                    $device->android_sdkInt = $request->sdkInt;
                    $device->uuid = $request->uuid;
                    $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                    $device->ads_id = $request->ads_id;
                    $device->notification_token = $notification_token;
                    $device->save();
                }
            }

            UsersActivity::create([
                'nip' => $request->username,
                'activity' => 'sign in',
                'device' => $request->uuid,
            ]);

            $jabatan_struktural_id = Auth::user()->v2Profile->jabatan_struktural_id;

            $jabatan_fungsional_id = Auth::user()->v2Profile->jabatan_fungsional_id;

            $jabatan_fungsional_umum_id = Auth::user()->v2Profile->jabatan_fungsional_umum_id;

            $jabatan_id = !empty($jabatan_struktural_id) ? $jabatan_struktural_id : (!empty($jabatan_fungsional_id) ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);

            $unor_induk_id = KelompokAbsen::select('unor_induk_id')->where('unor_id', Auth::user()->v2Profile->unor_id)->first()->unor_induk_id;

            $unor_induk_nama = KelompokAbsen::select('unor_nama')->where('unor_id', $unor_induk_id)->first()->unor_nama;

            $response = [
                'login_success' => true,
                'message' => "Login Success",
                'result' => "allow",
                'is_superuser' => false,
                'user' => [
                    'PNS_ID' =>  Auth::user()->v2Profile->id,
                    'NIP_BARU' =>  (string)Auth::user()->v2Profile->nip_baru,
                    'NIK' =>  (string)Auth::user()->v2Profile->nik,
                    'NAMA' => str_replace("KORTI ", "KORTI", str_replace("AMALIA ", "AMALIA", Auth::user()->v2Profile->nama)),
                    'ALAMAT' => str_replace(["   ", "  "], " ", Auth::user()->v2Profile->alamat),
                    'TEMPAT_LAHIR' => Auth::user()->v2Profile->tempat_lahir,
                    'TANGGAL_LAHIR' => Carbon::createFromFormat(
                        'd-m-Y',
                        Auth::user()->v2Profile->tanggal_lahir,
                        'Asia/Jakarta'
                    )->format('Y-m-d'),
                    'NO_HP' => Auth::user()->v2Profile->no_hp,
                    'JENIS_JABATAN_ID' => Auth::user()->v2Profile->jenis_jabatan_id,
                    'JABATAN_ID' => $jabatan_id,
                    'JABATAN_NAMA' => str_replace("  ", " ", Auth::user()->v2Profile->jabatan_nama),
                    'JENIS_KELAMIN' => Auth::user()->v2Profile->jenis_kelamin,
                    'UNOR_ID' => Auth::user()->v2Profile->unor_id,
                    'UNOR_NAMA' => str_replace("  ", " ", Auth::user()->v2Profile->unor_nama),
                    'UNOR_INDUK_ID' => $unor_induk_id,
                    'UNOR_INDUK_NAMA' => $unor_induk_nama,
                    'JENIS_KEPEGAWAIAN' => Auth::user()->jenis_kepegawaian,
                ],
            ];

            $response['token_type'] = $token['token_type'];
            $response['expires_in'] = $token['expires_in'];
            $response['expires_at'] = Carbon::parse(strtotime(Carbon::now()) + $token['expires_in'], 'Asia/Jakarta')->translatedFormat('j F Y H:i');
            $response['access_token'] = $token['access_token'];
            $response['refresh_token'] = $token['refresh_token'];
        } else {
            $device = DeviceNonAsn::select('username')
                ->where('uuid', $uuid)
                ->where('is_active', true)
                ->with(['dataNonAsn' => function ($query) {
                    $query->select('username', 'nama');
                }])
                ->orderBy('id', 'ASC')
                ->first();

            if (isset($device)) {
                if ($device->uuid != $request->uuid) {

                    $log = new LogMUltiLogin();
                    $log->nip = $request->username;
                    $log->device_uuid = $request->uuid;
                    $log->save();

                    return response([
                        'message' => 'Perangkat tidak terdaftar!'
                    ], 400);
                }
            }


            $token = $this->getTokenAndRefreshToken($request->username, $request->password, $request->client_id, $request->client_secret);

            $notification_token = $request->fcm ?? null;

            if (!empty($request->brand) && ($request->uuid != 'h0mao47z-eruk-8h0l-e6vg-nxk7c82oj8jt' && !str_contains($request->uuid, 'vxi45d2n-kyt9-sbkg-pkz0-fw41ldvcdgiy'))) {

                $app_id = ApplicationList::select('id')->where('app_code', $request->app_code)->first()->id;

                $is_allowed = ApplicationRules::select('is_allowed')->where('app_id', $app_id)->where('rule_name', 'like', '%multiple%')->first()->is_allowed;

                $registered_device = DeviceNonAsn::where('username', $request->username)
                    ->where('uuid', '!=', null)
                    ->orWhere('uuid', '!=', '')
                    ->where('nip_baru', $request->username)
                    ->get();

                $arr_uuid = $registered_device->where('is_active', true)->pluck('uuid')->toArray();

                $tokenPayload = explode('.', $token['access_token'])[1];

                if ($registered_device->count() > 0) {
                    if ($is_allowed) {
                        if (in_array($request->uuid, $arr_uuid)) {
                            $device = DeviceNonAsn::where('uuid', $request->uuid)->first();
                            $device->username = $request->username;
                            $device->app_version = $request->app_version;
                            $device->device = $request->device;
                            $device->device_brand = $request->brand;
                            $device->device_model = $request->model;
                            $device->device_id = $request->deviceId;
                            $device->device_abis = $request->supportedAbis;
                            $device->android_release = $request->release;
                            $device->android_sdkInt = $request->sdkInt;
                            $device->uuid = $request->uuid;
                            $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                            $device->is_active = true;
                            $device->ads_id = $request->ads_id;
                            $device->notification_token = $notification_token;
                            $device->save();
                        } else {
                            if ($registered_device->where('is_active', true)->count() > 9) {
                                $locked_log = LockedAccount::where('nip', $request->username)->where('is_locked', true)->first();
                                if (empty($locked_log)) {
                                    $locked_log = new LockedAccount();
                                    $locked_log->nip = $request->username;
                                    $locked_log->device = $request->uuid;
                                    $locked_log->save();

                                    $device = new DeviceNonAsn();
                                    $device->username = $request->username;
                                    $device->app_version = $request->app_version;
                                    $device->device = $request->device;
                                    $device->device_brand = $request->brand;
                                    $device->device_model = $request->model;
                                    $device->device_id = $request->deviceId;
                                    $device->device_abis = $request->supportedAbis;
                                    $device->android_release = $request->release;
                                    $device->android_sdkInt = $request->sdkInt;
                                    $device->uuid = $request->uuid;
                                    $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                                    $device->ads_id = $request->ads_id;
                                    $device->notification_token = $notification_token;
                                    $device->save();
                                } else {
                                    $locked_log->counted = $locked_log->counted + 1;
                                    $locked_log->save();

                                    $nama = NonAsn::select('nama')->where('username', $request->username)->first()->nama;

                                    $device = new UnknownDevice();
                                    $device->nip_baru = $request->username;
                                    $device->app_version = $request->app_version;
                                    $device->device = $request->device;
                                    $device->device_brand = $request->brand;
                                    $device->device_model = $request->model;
                                    $device->device_id = $request->deviceId;
                                    $device->device_abis = $request->supportedAbis;
                                    $device->android_release = $request->release;
                                    $device->android_sdkInt = $request->sdkInt;
                                    $device->uuid = $request->uuid;
                                    $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                                    $device->ads_id = $request->ads_id;
                                    $device->notification_token = $notification_token;
                                    $device->save();

                                    return response([
                                        'login_success' => false,
                                        'message' => 'Yth. ' . $nama . ', akun anda sedang dibekukan.',
                                    ], 400);
                                }
                            } else {
                                $device = new DeviceNonAsn();
                                $device->username = $request->username;
                                $device->app_version = $request->app_version;
                                $device->device = $request->device;
                                $device->device_brand = $request->brand;
                                $device->device_model = $request->model;
                                $device->device_id = $request->deviceId;
                                $device->device_abis = $request->supportedAbis;
                                $device->android_release = $request->release;
                                $device->android_sdkInt = $request->sdkInt;
                                $device->uuid = $request->uuid;
                                $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                                $device->ads_id = $request->ads_id;
                                $device->notification_token = $notification_token;
                                $device->save();
                            }
                        }
                    } else {
                        if (in_array($request->uuid, $arr_uuid)) {
                            $device = DeviceNonAsn::where('uuid', $request->uuid)->first();
                            $device->username = $request->username;
                            $device->app_version = $request->app_version;
                            $device->device = $request->device;
                            $device->device_brand = $request->brand;
                            $device->device_model = $request->model;
                            $device->device_id = $request->deviceId;
                            $device->device_abis = $request->supportedAbis;
                            $device->android_release = $request->release;
                            $device->android_sdkInt = $request->sdkInt;
                            $device->uuid = $request->uuid;
                            $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                            $device->ads_id = $request->ads_id;
                            $device->notification_token = $notification_token;
                            $device->save();
                        } else {
                            return response(array(
                                'login_success' => false,
                                'message' => "Anda sudah login pada perangkat lain.",
                            ), 400);
                        }
                    }
                } else {
                    $device = new DeviceNonAsn();
                    $device->username = $request->username;
                    $device->app_version = $request->app_version;
                    $device->device = $request->device;
                    $device->device_brand = $request->brand;
                    $device->device_model = $request->model;
                    $device->device_id = $request->deviceId;
                    $device->device_abis = $request->supportedAbis;
                    $device->android_release = $request->release;
                    $device->android_sdkInt = $request->sdkInt;
                    $device->uuid = $request->uuid;
                    $device->token_id = json_decode(base64_decode($tokenPayload))->jti;
                    $device->ads_id = $request->ads_id;
                    $device->notification_token = $notification_token;
                    $device->save();
                }
            }

            UsersActivity::create([
                'nip' => $request->username,
                'activity' => 'sign in',
                'device' => $request->uuid,
            ]);

            $nonAsn = Auth::user()->nonAsn;

            $response = [
                'message' => "Login Success",
                'result' => "allow",
                'is_superuser' => false,
                'user' => [
                    'ID' =>  $nonAsn->id,
                    'USERNAME' =>  $nonAsn->username,
                    'NAMA' => $nonAsn->nama,
                    'JABATAN_ID' => $nonAsn->jabatan_id,
                    'JABATAN_NAMA' => $nonAsn->jabatan,
                    'JENIS_KELAMIN' => $nonAsn->jenis_kelamin,
                    'JENIS_KEPEGAWAIAN' => Auth::user()->jenis_kepegawaian,
                ],
            ];

            $response['token_type'] = $token['token_type'];
            $response['expires_in'] = $token['expires_in'];
            $response['expires_at'] = Carbon::parse(strtotime(Carbon::now()) + $token['expires_in'], 'Asia/Jakarta')->translatedFormat('j F Y H:i');
            $response['access_token'] = $token['access_token'];
            $response['refresh_token'] = $token['refresh_token'];
        }

        return response($response, 200);
    }

    public function getTokenAndRefreshToken($username, $password, $client_id, $client_secret)
    {
        $http = new Client;
        $response = $http->request('POST', 'https://simpeg.padang.go.id/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'username' => $username,
                'password' => $password,
                // 'scope' => $username,
            ],
        ]);
        $result = json_decode((string) $response->getBody(), true);
        return $result;
    }

    public function refreshToken($username, Request $request)
    {
        $http = new Client;
        $result = array(
            "token_type" => "Bearer",
            "expires_in" =>  31536000,
            "access_token" => "-",
            "refresh_token" => "-"
        );

        $status_code = 400;

        $response = $http->request('POST', 'https://simpeg.padang.go.id/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => $request->client_id,
                'client_secret' => $request->client_secret,
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            $result = json_decode((string) $response->getBody(), true);
            $status_code = 200;
        }

        return response($result, $status_code);
    }

    public function logout(Request $request)
    {

        $login = $request->validate([
            'username' => 'required'
        ]);

        $user = User::where('username', $request->username);

        $success = false;
        $message = null;

        if ($user->count() > 0) {

            $success = true;
            $message = 'Logout Success';

            $tokens = Token::where('user_id', $user->first()->id)->get();

            foreach ($tokens as $token) {
                $token->revoke();
            }
        } else {
            $success = false;
            $message = 'User tidak ditemukan!';
        }

        return response([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function masterOrganisasi(Request $request)
    {
        $q = $request->q;
        $data_organisasi = MasterOrganisasi::select('unor_induk_id as UNOR_INDUK_ID', 'unor_induk_nama as UNOR_INDUK_NAMA')->where('UNOR_INDUK_ID', '!=', 'ff80808132cd33330132d2e591c25e5f');
        $data_puskesmas = MasterPuskesmas::select('unor_id as UNOR_INDUK_ID', 'unor_nama as UNOR_INDUK_NAMA')->get();

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($data_organisasi->count() > 0) {
            $arr_data_organisasi = $data_organisasi->get()->toArray();
            $arr_data_puskesmas = $data_puskesmas->toArray();

            $success = true;
            $message = 'Data organisasi ditemukan';
            $data = array_merge($arr_data_organisasi, $arr_data_puskesmas);
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        if (!empty($q) && $q == 1) {
            return response([
                'success' => $success,
                'message' => $message,
                'total' => $data_organisasi->count(),
                'data' => collect($data)->map(function ($value) {
                    return array(
                        'UNOR_INDUK_ID' => $value['UNOR_INDUK_ID'],
                        'UNOR_INDUK_NAMA' => str_replace('Uptd', 'UPTD', ucwords(strtolower($value['UNOR_INDUK_NAMA']))),
                        'UNOR_INDUK_TYPE' => 'Pemerintah Kota Padang',
                    );
                })->sortBy('UNOR_INDUK_NAMA')->prepend(array(
                    'UNOR_INDUK_ID' => '0',
                    'UNOR_INDUK_NAMA' => 'Masyarakat',
                    'UNOR_INDUK_TYPE' => 'Publik',
                ))->values()
            ], $code);
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $data_organisasi->count(),
            'data' => collect($data)->map(function ($value) {
                return array(
                    'UNOR_INDUK_ID' => $value['UNOR_INDUK_ID'],
                    'UNOR_INDUK_NAMA' => str_replace('Uptd', 'UPTD', ucwords(strtolower($value['UNOR_INDUK_NAMA']))),
                    'UNOR_INDUK_TYPE' => 'Pemerintah Kota Padang',
                );
            })->sortBy('UNOR_INDUK_NAMA')->values()
        ], $code);
    }

    public function masterJabatan(Request $request)
    {
        $data_jabatan = MasterJabatan::select('JABATAN_ID', 'JABATAN_NAMA', 'JENIS_JABATAN_NAMA')->where('UNOR_INDUK_ID', $request->unor_induk_id);

        $success = false;
        $message = null;
        $data = null;

        if ($data_jabatan->count() > 0) {
            $success = true;
            $message = 'Data jabatan ditemukan';
            $data = $data_jabatan->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $data_jabatan->count(),
            'data' => $data
        ]);
    }

    public function asnPerOrganisasi(Request $request)
    {
        $unor_id = KelompokAbsen::select('unor_id', 'unor_nama')->where('unor_induk_id', $request->unor_induk_id)->get();
        $unor_induk_nama = $unor_id->where('unor_id', $request->unor_induk_id)->first()['unor_nama'];
        $unor_id_arr = $unor_id->pluck('unor_id')->toArray();
        $data_utama =  DataUtama::select(
            'id as PNS_ID',
            'nip_baru as NIP_BARU',
            'nama as NAMA',
            'nik as NIK',
            'unor_nama as UNOR_NAMA',
            'eselon as ESELON',
            'eselon_id as ESELON_ID',
            'kedudukan_pns_id as KEDUDUKAN_PNS_ID',
            'kedudukan_pns_nama as KEDUDUKAN_PNS_NAMA',
            'jenis_jabatan_id as JENIS_JABATAN_ID',
            'jenis_jabatan as JENIS_JABATAN',
            'jabatan_struktural_id as JABATAN_STRUKTURAL_ID',
            'jabatan_fungsional_id as JABATAN_FUNGSIONAL_ID',
            'jabatan_fungsional_umum_id as JABATAN_FUNGSIONAL_UMUM_ID',
            'gol_ruang_akhir as GOL_NAMA',
            'jenis_jabatan as JENIS_JABATAN_NAMA',
            'tk_pendidikan_terakhir as PENDIDIKAN_TERAKHIR',
            'tempat_lahir as TEMPAT_LAHIR',
            'tanggal_lahir as TANGGAL_LAHIR',
            'tmt_cpns as TMT_CPNS',
            'jabatan_nama as JABATAN_NAMA'
        )->whereIn('unor_id', $unor_id_arr)->where('JABATAN_NAMA', 'not like', '%guru%')->whereIn('kedudukan_pns_id', ['01', '02', '15', '71'])->where('is_active', true)->get();

        $modified_data_utama = $data_utama->map(function ($data) {
            return array(
                'PNS_ID' => $data['PNS_ID'],
                'NIP_BARU' => $data['NIP_BARU'],
                'NAMA' => $data['NAMA'],
                'JENIS_JABATAN_ID' => $data['JENIS_JABATAN_ID'],
                'JENIS_JABATAN' => $data['JENIS_JABATAN'],
                'JABATAN_ID' => !empty($data['JABATAN_STRUKTURAL_ID']) ? $data['JABATAN_STRUKTURAL_ID'] : (!empty($data['JABATAN_FUNGSIONAL_ID']) ? $data['JABATAN_FUNGSIONAL_ID'] : $data['JABATAN_FUNGSIONAL_UMUM_ID']),
                'JABATAN_NAMA' => $data['JABATAN_NAMA'],
                'GOL_NAMA' =>  $data['GOL_NAMA'],
                'UNOR_NAMA' =>  $data['UNOR_NAMA'],
                'ESELON' =>  $data['ESELON'],
                'ESELON_ID' =>  $data['ESELON_ID'],
                'JENIS_JABATAN_NAMA' => $data['JENIS_JABATAN_NAMA'],
                'KEDUDUKAN_PNS_ID' => $data['KEDUDUKAN_PNS_ID'],
                'KEDUDUKAN_PNS_NAMA' => $data['KEDUDUKAN_PNS_NAMA'],
                'PENDIDIKAN_TERAKHIR' => $data['PENDIDIKAN_TERAKHIR'],
                'TANGGAL_LAHIR' => $data['TANGGAL_LAHIR'],
                'TMT_CPNS' => $data['TMT_CPNS'],
                'TEMPAT_LAHIR' => $data['TEMPAT_LAHIR']
            );
        });

        $arr_jabatan_id = $modified_data_utama->pluck('JABATAN_ID')->toArray();

        $kelas_jabatan = MasterKelasJabatan::whereIn('jabatan_id', $arr_jabatan_id)->get();

        $data_asn = $modified_data_utama->map(function ($data) use ($kelas_jabatan, $unor_induk_nama) {
            $kelas = $kelas_jabatan->where('jabatan_id', $data['JABATAN_ID'])->first()['kelas'] ?? 0;
            $kelas_jabatan = str_replace("\r", '', $kelas);
            return array(
                'PNS_ID' => $data['PNS_ID'],
                'NIP_BARU' => $data['NIP_BARU'],
                'NAMA' => $data['NAMA'],
                'JENIS_JABATAN_ID' => $data['JENIS_JABATAN_ID'],
                'JENIS_JABATAN' => $data['JENIS_JABATAN'],
                'JABATAN_ID' => $data['JABATAN_ID'],
                'JABATAN_NAMA' => str_replace('  ', ' ', $data['JABATAN_NAMA']),
                'UNOR_INDUK_NAMA' => $unor_induk_nama,
                'UNOR_NAMA' => str_replace('  ', ' ', $data['UNOR_NAMA']),
                'ESELON' => $data['ESELON'],
                'ESELON_ID' => $data['ESELON_ID'],
                'GOL_NAMA' => $data['GOL_NAMA'],
                'JENIS_JABATAN_NAMA' => $data['JENIS_JABATAN_NAMA'],
                'KEDUDUKAN_PNS_ID' => $data['KEDUDUKAN_PNS_ID'],
                'KEDUDUKAN_PNS_NAMA' => $data['KEDUDUKAN_PNS_NAMA'],
                'PENDIDIKAN_TERAKHIR' => $data['PENDIDIKAN_TERAKHIR'],
                'TEMPAT_LAHIR' => $data['TEMPAT_LAHIR'],
                'TANGGAL_LAHIR' => $data['TANGGAL_LAHIR'],
                'TMT_CPNS' => date('Y-m-d', strtotime($data['TMT_CPNS'])),
                'KELAS_JABATAN' => (int)$kelas_jabatan == 0 ? null : (int)$kelas_jabatan
            );
        });

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($data_utama->count() > 0) {
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $data_asn;
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $data_utama->count(),
            'data' => $data
        ], $code);
    }

    public function profileAsn($nip_or_pns_id)
    {

        // $data_organisasi = MasterOrganisasi::all();

        $profile_asn = DataUtama::select(
            'id as PNS_ID',
            'nip_baru as NIP_BARU',
            'nik as NIK',
            'nama as NAMA',
            'alamat as ALAMAT',
            'tempat_lahir as TEMPAT_LAHIR',
            'tanggal_lahir as TANGGAL_LAHIR',
            'jenis_kelamin as JENIS_KELAMIN',
            'no_hp as NO_HP',
            'jabatan_nama as JABATAN_NAMA',
            'jenis_jabatan_id as JENIS_JABATAN_ID',
            'jabatan_struktural_id as JABATAN_STRUKTURAL_ID',
            'jabatan_fungsional_id as JABATAN_FUNGSIONAL_ID',
            'jabatan_fungsional_umum_id as JABATAN_FUNGSIONAL_UMUM_ID',
            'gol_ruang_akhir as GOL_NAMA',
            'jenis_jabatan as JENIS_JABATAN_NAMA',
            'tk_pendidikan_terakhir as PENDIDIKAN_TERAKHIR',
            'eselon as ESELON',
            'tmt_jabatan as TMT_JABATAN',
            'tmt_gol_akhir as TMT_GOLONGAN',
            'unor_id as UNOR_ID',
            'unor_nama as UNOR_NAMA',
            'unor_induk_id as UNOR_INDUK_ID',
            'unor_induk_nama as UNOR_INDUK_NAMA'
        )->where(strlen($nip_or_pns_id) == 18 ? 'nip_baru' : 'id', $nip_or_pns_id)->get()->map(function ($val) {

            $tanggal_lahir = Carbon::createFromFormat('d-m-Y', $val['TANGGAL_LAHIR'], 'Asia/Jakarta')->format('Y-m-d');
            $tmt_jabatan = Carbon::createFromFormat('d-m-Y', $val['TMT_JABATAN'], 'Asia/Jakarta')->format('Y-m-d');
            $tmt_golongan = Carbon::createFromFormat('d-m-Y', $val['TMT_GOLONGAN'], 'Asia/Jakarta')->format('Y-m-d');
            $jabatan_id = !empty($val['JABATAN_STRUKTURAL_ID']) ? $val['JABATAN_STRUKTURAL_ID'] : (!empty($val['JABATAN_FUNGSIONAL_ID']) ? $val['JABATAN_FUNGSIONAL_ID'] : $val['JABATAN_FUNGSIONAL_UMUM_ID']);
            $kelas_jabatan = MasterKelasJabatan::where('jabatan_id', $jabatan_id)->first()['kelas'] ?? 0;
            $kelas = str_replace("\r", '', $kelas_jabatan);
            $kelompok_absen = KelompokAbsen::select('unor_induk_id')->where('unor_id', $val['UNOR_ID'])->first();
            $unor_induk_id = $kelompok_absen->unor_induk_id;
            $unor_induk_nama = KelompokAbsen::select('unor_nama')->where('unor_id', $unor_induk_id)->first()->unor_nama;
            return [
                'NIK' => $val['NIK'],
                'PNS_ID' => $val['PNS_ID'],
                'NIP_BARU' => $val['NIP_BARU'],
                'NAMA' => $val['NAMA'],
                'ALAMAT' => $val['ALAMAT'],
                'JABATAN_NAMA' => $val['JABATAN_NAMA'],
                'GOL_NAMA' => $val['GOL_NAMA'],
                'JENIS_JABATAN_NAMA' => $val['JENIS_JABATAN_NAMA'],
                'JABATAN_ID' => $jabatan_id,
                'JENIS_JABATAN_ID' => $val['JENIS_JABATAN_ID'],
                'KELAS_JABATAN' => $kelas,
                'PENDIDIKAN_TERAKHIR' => $val['PENDIDIKAN_TERAKHIR'],
                'ESELON' => $val['ESELON'],
                'TEMPAT_LAHIR' => $val['TEMPAT_LAHIR'],
                'TANGGAL_LAHIR' => $tanggal_lahir,
                'JENIS_KELAMIN' => $val['JENIS_KELAMIN'],
                'NO_HP' => $val['NO_HP'],
                'TMT_JABATAN' => $tmt_jabatan,
                'TMT_GOLONGAN' => $tmt_golongan,
                'UNOR_ID' => $val['UNOR_ID'],
                'UNOR_NAMA' => $val['UNOR_NAMA'],
                'UNOR_INDUK_ID' => $unor_induk_id,
                'UNOR_INDUK_NAMA' => $unor_induk_nama,
                // 'UNOR_INDUK_NAMA' => $organisasi->unor_induk_nama ?? $val['UNOR_INDUK_NAMA'],
            ];
        });

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($profile_asn->count() > 0) {
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $profile_asn->first();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $profile_asn->count(),
            'data' => $data
        ], $code);
    }

    public function asn(Request $request)
    {
        $nip = $request->input('nip');

        $nama = $request->input('nama');

        $organisasi = $request->input('organisasi');

        $profile_asn = null;

        if (!empty($nip) && !empty($nama)) {
            $profile_asn = DataUtama::select(
                'id as PNS_ID',
                'nip_baru as NIP_BARU',
                'nama as NAMA',
                'jabatan_nama as JABATAN_NAMA',
                'gol_ruang_akhir as GOL_NAMA',
                'jenis_jabatan as JENIS_JABATAN_NAMA',
                'unor_id as UNOR_ID',
                'unor_nama as UNOR_NAMA',
                'unor_induk_id as UNOR_INDUK_ID',
                'unor_induk_nama as UNOR_INDUK_NAMA'
            )->where('nip_baru', $nip)->where('nama', $nama);
        } else if (!empty($nip)) {
            $profile_asn = DataUtama::select(
                'id as PNS_ID',
                'nip_baru as NIP_BARU',
                'nama as NAMA',
                'jabatan_nama as JABATAN_NAMA',
                'gol_ruang_akhir as GOL_NAMA',
                'jenis_jabatan as JENIS_JABATAN_NAMA',
                'unor_id as UNOR_ID',
                'unor_nama as UNOR_NAMA',
                'unor_induk_id as UNOR_INDUK_ID',
                'unor_induk_nama as UNOR_INDUK_NAMA'
            )->where('nip_baru', $nip);
        } else if (!empty($nama)) {
            $profile_asn = DataUtama::select(
                'id as PNS_ID',
                'nip_baru as NIP_BARU',
                'nama as NAMA',
                'jabatan_nama as JABATAN_NAMA',
                'gol_ruang_akhir as GOL_NAMA',
                'jenis_jabatan as JENIS_JABATAN_NAMA',
                'unor_id as UNOR_ID',
                'unor_nama as UNOR_NAMA',
                'unor_induk_id as UNOR_INDUK_ID',
                'unor_induk_nama as UNOR_INDUK_NAMA'

            )->where('nama', 'like', $nama . '%');
        } else if (!empty($organisasi) && !empty($nip)) {
            $profile_asn = DataUtama::select(
                'id as PNS_ID',
                'nip_baru as NIP_BARU',
                'nama as NAMA',
                'jabatan_nama as JABATAN_NAMA',
                'gol_ruang_akhir as GOL_NAMA',
                'jenis_jabatan as JENIS_JABATAN_NAMA',
                'unor_id as UNOR_ID',
                'unor_nama as UNOR_NAMA',
                'unor_induk_id as UNOR_INDUK_ID',
                'unor_induk_nama as UNOR_INDUK_NAMA'

            )->where('unor_nama', 'like', '%' . $organisasi . '%')->where('nip_baru', $nip);
        } else if (!empty($organisasi)) {
            $profile_asn = DataUtama::select(
                'id as PNS_ID',
                'nip_baru as NIP_BARU',
                'nama as NAMA',
                'jabatan_nama as JABATAN_NAMA',
                'gol_ruang_akhir as GOL_NAMA',
                'jenis_jabatan as JENIS_JABATAN_NAMA',
                'unor_id as UNOR_ID',
                'unor_nama as UNOR_NAMA',
                'unor_induk_id as UNOR_INDUK_ID',
                'unor_induk_nama as UNOR_INDUK_NAMA'

            )->where('unor_nama', 'like', '%' . $organisasi . '%');
        } else if (!empty($organisasi) && !empty($nama)) {
            $profile_asn = DataUtama::select(
                'id as PNS_ID',
                'nip_baru as NIP_BARU',
                'nama as NAMA',
                'jabatan_nama as JABATAN_NAMA',
                'gol_ruang_akhir as GOL_NAMA',
                'jenis_jabatan as JENIS_JABATAN_NAMA',
                'unor_id as UNOR_ID',
                'unor_nama as UNOR_NAMA',
                'unor_induk_id as UNOR_INDUK_ID',
                'unor_induk_nama as UNOR_INDUK_NAMA'

            )->where('unor_nama', 'like', '%' . $organisasi . '%')->where('nip_baru', 'like', '%' . $nama . '%');
        }

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($profile_asn->count() == 1) {
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $profile_asn->first();
        } else if ($profile_asn->count() > 1) {
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $profile_asn->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $profile_asn->count(),
            'data' => $data
        ], $code);
    }

    public function semuaAsn()
    {
        $profile_asn = DataUtama::select(
            'id as PNS_ID',
            'nip_baru as NIP_BARU',
            'nama as NAMA',
            'jabatan_nama as JABATAN_NAMA',
            'gol_ruang_akhir as GOL_NAMA',
            'jenis_jabatan as JENIS_JABATAN_NAMA',
            'unor_id as UNOR_ID',
            'unor_nama as UNOR_NAMA',
            'unor_induk_id as UNOR_INDUK_ID',
            'unor_induk_nama as UNOR_INDUK_NAMA',
            'alamat as ALAMAT',
            'tempat_lahir as TEMPAT_LAHIR',
            'tanggal_lahir as TANGGAL_LAHIR',
            'jenis_kelamin as JENIS_KELAMIN',
            'no_hp as NO_HP',
            'jabatan_nama as JABATAN_NAMA',
            'gol_ruang_akhir as GOL_NAMA',
            'jenis_jabatan as JENIS_JABATAN_NAMA',
            'eselon as ESELON',
            'tmt_jabatan as TMT_JABATAN',
            'tmt_gol_akhir as TMT_GOLONGAN',
            'tahun_lulus as TAHUN_LULUS',
            'tk_pendidikan_terakhir as TK_PENDIDIKAN_TERAKHIR'

        )->orderBy('NAMA', 'ASC');

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($profile_asn->count() > 0) {
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $profile_asn->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $profile_asn->count(),
            'data' => $data
        ], $code);
    }

    public function strukturOrganisasi($unor_induk_id)
    {
        $data_struktur = RefUnor::select('unor_id', 'unor_nama', 'eselon_id as jenis', 'unor_atasan_id', 'unor_induk_id')
            // ->where('unor_atasan_id',$unor_induk_id)
            // ->where('unor_nama','NOT LIKE','%SD N%')
            // ->where('unor_nama','NOT LIKE','%SMP N%')
            // ->where('unor_nama','NOT LIKE','%TK N%')
            // ->where('unor_nama','NOT LIKE','%SEKOLAH%')
            // ->orWhere('unor_id',$unor_induk_id)
            ->where('unor_id', $unor_induk_id)
            ->where('unor_nama', 'NOT LIKE', '%SD N%')
            ->where('unor_nama', 'NOT LIKE', '%SMP N%')
            ->where('unor_nama', 'NOT LIKE', '%TK N%')
            ->where('unor_nama', 'NOT LIKE', '%SEKOLAH%')
            ->orderBy('jenis');

        $success = false;
        $message = null;
        $data = null;

        if ($data_struktur->count() > 0) {
            $success = true;
            $message = 'Data Struktur Organisasi ditemukan';
            $data = $data_struktur->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $data_struktur->count(),
            'data' => $data
        ]);
    }

    public function masterPuskemas()
    {
        $dataPuskemas = MasterPuskesmas::select(
            'unor_id as UNOR_ID',
            'unor_nama as UNOR_NAMA'
        );

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($dataPuskemas->count() > 0) {
            $success = true;
            $message = 'Data Puskemas ditemukan';
            $data = $dataPuskemas->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $dataPuskemas->get()->count(),
            'data' => $data
        ], $code);
    }

    public function masterSekolah()
    {
        MasterUnitOrganisasi::$withoutAppends = true;
        $dataSekolah = MasterUnitOrganisasi::select(
            'unor_id as UNOR_ID',
            'unor_nama as UNOR_NAMA'
        )->where('JENIS_ID', 1);

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($dataSekolah->count() > 0) {
            $success = true;
            $message = 'Data Sekolah ditemukan';
            $data = $dataSekolah->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $dataSekolah->get()->count(),
            'data' => $data
        ], $code);
    }

    public function masterKecamatan()
    {
        $dataKecamatan = MasterKecamatan::select(
            'unor_id as UNOR_ID',
            'unor_nama as UNOR_NAMA'
        );

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($dataKecamatan->count() > 0) {
            $success = true;
            $message = 'Data Kecamatan ditemukan';
            $data = $dataKecamatan->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $dataKecamatan->get()->count(),
            'data' => $data
        ], $code);
    }

    public function dataKelurahan($id_kecamatan)
    {
        $dataKelurahan = MasterKelurahan::select(
            'unor_id as UNOR_ID',
            'unor_nama as UNOR_NAMA'
        )->where('unor_atasan_id', $id_kecamatan);

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($dataKelurahan->count() > 0) {
            $success = true;
            $message = 'Data Kelurahan ditemukan';
            $data = $dataKelurahan->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $dataKelurahan->get()->count(),
            'data' => $data
        ], $code);
    }

    public function riwayatGolongan($nip_baru)
    {
        $dataGolongan = RiwayatGolongan::select(
            'idPns as PNS_ID',
            'nipBaru as NIP_BARU',

        )->where('nip_baru', $nip_baru)->get();

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($dataGolongan->count() > 0) {
            $success = true;
            $message = 'Data riwayat golongan ditemukan';
            $data = $dataGolongan->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $dataGolongan->count(),
            'data' => $data
        ], $code);
    }

    public function riwayatJabatan($nip_baru)
    {
        $dataJabatan = RiwayatJabatan::select(
            'idPns as PNS_ID',
            'nipBaru as NIP_BARU',
            'tmtJabatan as JABATAN_TMT',
            'namaJabatan as JABATAN_NAMA',
            'jabatanFungsionalNama as JABATAN_FUNGSIONAL_NAMA',
            'jabatanFungsionalUmumNama as JABATAN_FUNGSIONAL_UMUM_NAMA',
            'eselon as ESELON',
            'unorId as UNOR_ID',
            'unorNama as UNOR_NAMA',
            'unorIndukId as UNOR_INDUK_ID',
            'unorIndukNama as UNOR_INDUK_NAMA',

        )->where('nipBaru', $nip_baru);

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($dataJabatan->count() > 0) {
            $success = true;
            $message = 'Data riwayat jabatan ditemukan';
            $data = $dataJabatan->get()->map(function ($value) {
                return [
                    'PNS_ID' => $value['PNS_ID'],
                    'NIP_BARU' => $value['NIP_BARU'],
                    'JABATAN_TMT' => Carbon::createFromFormat('d-m-Y', $value['JABATAN_TMT'])->format('Y-m-d'),
                    'JABATAN_TMT_MONTH_NAME' => $this->setMonthName(Carbon::createFromFormat('d-m-Y', $value['JABATAN_TMT'])->format('m')),
                    'JABATAN_NAMA' => $value['JABATAN_NAMA'] != null ? $value['JABATAN_NAMA'] : ($value['JABATAN_FUNGSIONAL_NAMA'] != null ? $value['JABATAN_FUNGSIONAL_NAMA'] : $value['JABATAN_FUNGSIONAL_UMUM_NAMA']),
                    'JABATAN_NAMAA' => $value['JABATAN_NAMA'],
                    'JABATAN_FUNGSIONAL_NAMA' => $value['JABATAN_FUNGSIONAL_NAMA'],
                    'JABATAN_FUNGSIONAL_UMUM_NAMA' => $value['JABATAN_FUNGSIONAL_UMUM_NAMA'],
                    'ESELON' => $value['ESELON'],
                    'UNOR_ID' => $value['UNOR_ID'],
                    'UNOR_NAMA' => $value['UNOR_NAMA'],
                    'UNOR_INDUK_ID' => $value['UNOR_INDUK_ID'],
                    'UNOR_INDUK_NAMA' => $value['UNOR_INDUK_NAMA'],
                ];
            })->sortByDesc('JABATAN_TMT')->values();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $dataJabatan->count(),
            'data' => $data
        ], $code);
    }

    public function riwayatPendidikan($nip_baru)
    {
        $dataPendidikan = RiwayatPendidikan::where('nip_baru', $nip_baru)->get();

        $success = false;
        $message = null;
        $data = null;
        $code = 200;

        if ($dataPendidikan->count() > 0) {
            $success = true;
            $message = 'Data riwayat pendidikan ditemukan';
            $data = $dataPendidikan->get();
        } else {
            $success = false;
            $message = 'Data tidak ditemukan';
            $code = 400;
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $dataPendidikan->count(),
            'data' => $data
        ], $code);
    }

    public function verifyToken($nip_baru)
    {
        if (Auth::check()) {
            return response([
                'payload' => "Token verified.",
                'isValid' => true,
                'NIP_BARU' =>  (string)$nip_baru,
            ], 200);
        } else {
            return response([
                'payload' => "You're not Authorized to access this resource."
            ], 401);
        }
    }

    public function getStruktural($nip)
    {

        $unor_id = DataUtama::select('unor_id')->where('nip_baru', $nip)->first()->unor_id;

        $unor_induk_id = KelompokAbsen::select('unor_induk_id')->where('unor_id', $unor_id)->first()->unor_induk_id;

        $arr_unor_id = KelompokAbsen::select('unor_id')->where('unor_induk_id', $unor_induk_id)->get()->pluck('unor_id')->toArray();

        $pegawai = DataUtama::select('nip_baru', 'nama', 'jabatan_struktural_id', 'jabatan_nama', 'eselon_id', 'unor_induk_id')->where('nip_baru', $nip)->first();

        $data_kepala_puskesmas = KepalaPuskesmas::select('unor_id', 'nip')->where('is_active', true)->get();

        $plt = Plt::select('nip')->where('status', true)->whereIn('unor_id', $arr_unor_id)->get();

        $kepala_bagian_jabatan_id = [
            '8ae482873580873501358564cee3081e',
            '8ae48287358087350135854195b478c1',
            '8ae48287358087350135855494e200f9',
            '8ae48287358087350135855749e3024e',
            '8ae482a75dcb9d79015de3b143171acb',
            'ff80808132f812360132fc174f0f4373',
            'ff80808132f812360132fc21c3de474f',
            '8ae48287358087350135849f51552ebf',
            '8ae482873ea2d064013eb0a680ba0e4b',
            '8ae482865989f86801598b72cf8d47f3'
        ];

        $asisten_jabatan_id = [
            '8ae48287358087350135849e531c2e56',
            '8ae482873580873501358559ce270377',
            '8ae48287358087350135854ffea97eb8',
        ];

        $unor_id_bagian = [
            '8ae482873580873501358564cee3081e',
            '8ae48287358087350135854195b478c1',
            '8ae48287358087350135855494e200f9',
            '8ae48287358087350135855749e3024e',
            '8ae482a75dcb9d79015de3b143171acb',
            'ff80808132f812360132fc174f0f4373',
            'ff80808132f812360132fc21c3de474f',
            '8ae48287358087350135849f51552ebf',
            '8ae482865989f86801598b72cf8d47f3',
            '8ae482873ea2d064013eb0a680ba0e4b'
        ];

        $stafahli_jabatan_id = [
            '8ae482a75dcb9d79015de39a64330849'
        ];

        if (in_array($unor_induk_id, $data_kepala_puskesmas->pluck('unor_id')->toArray())) {
            $kepala_puskesmas = DataUtama::select('nip_baru', 'nama', 'jabatan_fungsional_id as jabatan_struktural_id', 'jabatan_nama', 'eselon_id')
                ->where('is_active', true)
                ->where('nip_baru', $data_kepala_puskesmas->where('unor_id', $unor_induk_id)->first()->nip)
                ->first();
        }

        if (in_array($pegawai->jabatan_struktural_id, $kepala_bagian_jabatan_id)) {
            $asisten = DataUtama::select('nip_baru', 'nama', 'jabatan_struktural_id', 'jabatan_nama', 'eselon_id')
                ->where('is_active', true)
                ->whereIn('jabatan_struktural_id', $asisten_jabatan_id)
                ->get();
        }

        if (in_array($unor_id, $unor_id_bagian)) {
            $stafahli = DataUtama::select('nip_baru', 'nama', 'jabatan_struktural_id', 'jabatan_nama', 'eselon_id')
                ->where('is_active', true)
                ->whereIn('jabatan_struktural_id', $stafahli_jabatan_id)
                ->get();
        }

        if ($plt->count() > 0) {
            $data_plt = DataUtama::select('nip_baru', 'nama', 'jabatan_struktural_id', 'jabatan_fungsional_id', 'jabatan_fungsional_umum_id', 'jabatan_nama', 'eselon_id')
                ->where('is_active', true)
                ->whereIn('nip_baru', $plt->pluck('nip')->toArray())
                ->get();
        }

        $pegawais = collect([]);

        if (
            $pegawai->eselon_id == '22' ||
            (str_contains($pegawai->jabatan_nama, 'CAMAT') && $pegawai->eselon_id == '31') ||
            (str_contains($pegawai->jabatan_nama, 'KEPALA BAGIAN') && $pegawai->eselon_id == '31' && $pegawai->unor_induk_id != '8ae482865989f86801598b7d46b05110') ||
            (str_contains($pegawai->jabatan_nama, 'Kepala BAGIAN') && $pegawai->eselon_id == '31' && $pegawai->unor_induk_id != '8ae482865989f86801598b7d46b05110') ||
            (str_contains($pegawai->jabatan_nama, 'Kepala UNIT') && $pegawai->eselon_id == '31' && $pegawai->unor_induk_id != '8ae482865989f86801598b7d46b05110')
        ) {
            $pegawais = DataUtama::select('nip_baru', 'nama', 'jabatan_struktural_id', 'jabatan_nama', 'eselon_id')->where('is_active', true)->whereIn('unor_id', $arr_unor_id)->where('eselon_id', $pegawai->eselon_id)->orderBy('eselon_id', "ASC")->get();
        } else {
            $pegawais = DataUtama::select('nip_baru', 'nama', 'jabatan_struktural_id', 'jabatan_nama', 'eselon_id')->where('is_active', true)->whereIn('unor_id', $arr_unor_id)->where('eselon_id', '<', $pegawai->eselon_id)->orderBy('eselon_id', "ASC")->get();
        }

        if (isset($kepala_puskesmas)) {
            $pegawais->push($kepala_puskesmas);
        }

        if (isset($asisten)) {
            foreach ($asisten as $x) {
                $pegawais->push($x);
            }
        }

        if (isset($stafahli)) {
            foreach ($stafahli as $x) {
                $pegawais->push($x);
            }
        }

        if (isset($data_plt)) {
            $mapped_plt = $data_plt->map(function ($value) {
                $jabatan_struktural_id = $value['jabatan_struktural_id'];
                $jabatan_fungsional_id = $value['jabatan_fungsional_id'];
                $jabatan_fungsional_umum_id = $value['jabatan_fungsional_umum_id'];
                $jabatan_id = $jabatan_struktural_id != '' ? $jabatan_struktural_id : ($jabatan_fungsional_id != '' ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);
                return array(
                    'nip_baru' => $value['nip_baru'],
                    'nama' => $value['nama'],
                    'jabatan_struktural_id' => $jabatan_id,
                    'jabatan_nama' => $value['jabatan_nama'],
                    'eselon_id' => $value['eselon_id']
                );
            });

            foreach ($mapped_plt as $x) {
                $pegawais->push($x);
            }
        }

        $success = false;

        $message = null;

        $data = null;

        $code = 200;

        if ($pegawais->where('jabatan_struktural_id', '!=', '')->count() > 0) {

            $success = true;

            $message = 'Data pegawai ditemukan';

            $data = $pegawais->where('jabatan_struktural_id', '!=', '')->map(function ($val) {
                return array(
                    'nip_baru' => $val['nip_baru'],
                    'nama' => $val['nama'],
                    'jabatan_struktural_id' => $val['jabatan_struktural_id'],
                    'jabatan_nama' => strtoupper($val['jabatan_nama']),
                    'eselon_id' => $val['eselon_id']
                );
            })->values();
        } else {

            $success = false;

            $message = 'Data tidak ditemukan';

            $code = 400;
        }

        return response([

            'success' => $success,

            'message' => $message,

            'total' => $pegawais->where('jabatan_struktural_id', '!=', '')->count(),

            'data' => $data

        ], $code);
    }

    private function setMonthName($month)
    {
        switch ($month) {
            case 1:
                return 'Jan';
                break;
            case 2:
                return 'Feb';
                break;
            case 3:
                return 'Mar';
                break;
            case 4:
                return 'Apr';
                break;
            case 5:
                return 'Mei';
                break;
            case 6:
                return 'Jun';
                break;
            case 7:
                return 'Jul';
                break;
            case 8:
                return 'Ags';
                break;
            case 9:
                return 'Sep';
                break;
            case 10:
                return 'Okt';
                break;
            case 11:
                return 'Nov';
                break;
            case 12:
                return 'Des';
                break;
            default:
                return 'Jan';
        }
    }

    public function getAccessRights($jabatan_id)
    {
        $data_pegawai = DataUtama::select('id')
            ->where('jabatan_struktural_id', $jabatan_id)
            ->orWhere('jabatan_fungsional_id', $jabatan_id)
            ->orWhere('jabatan_fungsional_umum_id', $jabatan_id)
            ->get();

        if ($data_pegawai->count() > 0) {
            $personal_access = PersonalAccess::select('access_id', 'access_status')
                ->whereIn('pns_id', $data_pegawai->pluck('id')->toArray())
                ->where('access_status', true)
                ->with('access_data')
                ->get();
        }

        $access = PositionAccess::select('access_id', 'access_status')
            ->where('jabatan_id', $jabatan_id)
            ->where('access_status', true)
            ->with('access_data')
            ->get();

        if (isset($personal_access) && $access->count() == 0) {
            $access = $personal_access;
        }

        return $access;
    }

    public function saveSlide(Request $request)
    {

        $validator = Validator::make($request->all(), array(
            'slide_file' => 'required|mimes:jpeg,jpg,png|required|max:3072',
            'slide_no' => 'required'
        ));

        if ($validator->fails()) {
            return response(array(
                'success' => false,
                'message' => 'Permintaan tidak dapat diproses.'
            ), 400);
        }

        $file = $request->file('slide_file');

        $filename = time() . '.' . $file->getClientOriginalExtension();

        $status = Slide::where('slide_no', $request->slide_no)->update(array('status' => 0));

        if ($status) {
            $file->move(base_path("/storage/app/public/slide/"), $filename);
            $new_slide = new Slide();
            $new_slide->filename = $filename;
            $new_slide->slide_no = $request->slide_no;
            $new_slide->status = 1;
            $new_slide->save();
        }

        if (!empty($new_slide)) {
            return response(array(
                'success' => true,
                'message' => 'Slide berhasil diperbarui.'
            ), 200);
        }

        return response(array(
            'success' => false,
            'message' => 'Slide tidak bisa diperbarui.'
        ), 400);
    }

    public function cekHukdis($nip)
    {
        $success = false;

        $message = 'Data tidak ditemukan';

        $data = false;

        $isHukdis = false;

        $code = 404;

        $date = date('Y-m-d');

        $pns = DataUtama::select('id')->where('nip_baru', $nip)->first();

        if (empty($pns)) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'is_hukdis' => $isHukdis,
                'data' => $data,
            ), $code);
        }

        $rwHukdis = RiwayatHukdis::select('hukumanTanggal', 'akhirHukumTanggal', 'jenisHukumanNama')->where('pnsOrang', $pns->id)->get();

        if (empty($rwHukdis)) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'is_hukdis' => $isHukdis,
                'data' => $data,
            ), $code);
        }

        foreach ($rwHukdis as $x) {
            $hukumanTanggal = Carbon::createFromFormat('d-m-Y', $x->hukumanTanggal, 'Asia/Jakarta');
            $akhirHukumTanggal = Carbon::createFromFormat('d-m-Y', $x->akhirHukumTanggal, 'Asia/Jakarta');

            if ($date >= $hukumanTanggal->translatedFormat('Y-m-d') && $date <= $akhirHukumTanggal->translatedFormat('Y-m-d')) {
                $success = true;
                $message = 'Data ditemukan.';
                $isHukdis = true;
                $code = 200;
                $data = array(
                    'hukuman_tanggal' => $hukumanTanggal->translatedFormat('j F Y'),
                    'akhir_hukuman_tanggal' => $akhirHukumTanggal->translatedFormat('j F Y'),
                    'jenis_hukuman' => $x->jenisHukumanNama,
                );
            }
        }

        return response(array(
            'success' => $success,
            'message' => $message,
            'is_hukdis' => $isHukdis,
            'data' => $data,
        ), $code);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'password_lama' => 'required',
            'password_baru' => 'required|confirmed',
            'uuid' => 'required',
        ]);

        if ($validator->fails()) {
            return response(array(
                'success' => false,
                'message' => $validator->errors()->first()
            ), 400);
        }

        $user = User::select('username', 'password')->where('username', $request->nip)->first();

        $check = Hash::check($request->password_lama, $user->password);

        if (!$check) {
            return response(array(
                'success' => false,
                'message' => 'Kata sandi lama salah.'
            ), 400);
        }

        $status = User::where('username', $request->nip)->update(['password' => Hash::make($request->password_baru)]);

        if ($status) {
            UsersActivity::create([
                'nip' => $request->nip,
                'activity' => 'change password',
                'affected_table' => 'users',
                'affected_field' => 'password',
                'device' => $request->uuid,
            ]);
            return response(array(
                'success' => true,
                'message' => 'Update password berhasil.',
            ));
        }

        return response(array(
            'success' => false,
            'message' => 'Update Gagal.',
        ), 400);
    }

    public function getKepalaOpd()
    {
        $organisasi = MasterOrganisasi::select('unor_induk_id as unor_id', 'unor_induk_nama as unor_nama')->get();

        $unor_ids = $organisasi->pluck('unor_id')->toArray();

        $data = DataUtama::select('id', 'nip_baru', 'nama', 'jabatan_nama', 'eselon_id', 'unor_id', 'unor_nama')
            ->whereIn('unor_id', $unor_ids)
            ->where('eselon_id', '<=', '31')
            ->where('jabatan_nama', 'not like', '%kepala bagian%')
            ->orderBy('eselon_id', 'ASC')
            ->get();

        $mapped_data = $data->groupBy('unor_id')->map(function ($val) {
            $data = collect($val)->first();
            return array(
                'unor_id' => $data['unor_id'],
                'unor_nama' => $data['unor_nama'],
                'kepala_unit' => $data['nama'],
                'nip' => $data['nip_baru'],
                'jabatan' => $data['jabatan_nama'],
            );
        });

        return response(array(
            'success' => true,
            'message' => 'Data ditemukan.',
            'data' => $mapped_data->values()
        ), 200);
    }

    public function getListBerita(Request $request)
    {
        $search = $request->query('search');
        $data = null;
        $status_code = 404;
        $message = 'Data tidak ditemukan.';
        $success = false;

        if ($search == null) {
            $data = Berita::select('image_url', 'page_url', 'title', 'subtitle')
                ->where('is_active', true)
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->get();
            $status_code = 200;
            $message = 'Data ditemukan.';
            $success = true;
        } else {
            $data = Berita::select('image_url', 'page_url', 'title', 'subtitle')
                ->where('is_active', true)
                ->where('title', 'like', '%' . $search . '%')
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->get();
            $status_code = 200;
            $message = 'Data ditemukan.';
            $success = true;
        }

        return response(array(
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ), $status_code);
    }

    public function searchJabatanFungsional(Request $request)
    {
        $data = RefJabatanFungsional::select('jabatan_id', 'jabatan_nama')
            ->where('jabatan_nama', 'like', '%' . $request->q . '%')
            ->get();

        $data_umum = RefJabatanFungsionalUmum::select('jabatan_id', 'jabatan_nama')
            ->where('jabatan_nama', 'like', '%' . $request->q . '%')
            ->get();

        $merged_data = collect($data)->merge($data_umum);

        if ($data->count() > 0) {
            return response(array(
                'items' => $merged_data->map(
                    function ($val) {
                        return array(
                            'id' => $val['jabatan_id'],
                            'text' => $val['jabatan_nama'],
                        );
                    },
                )->all(),
                'count_filtered' => $merged_data->count(),
            ), 200);
        }

        return response([], 200);
    }

    public function searchPegawai(Request $request)
    {
        $data = DataUtama::select('nip_baru as id', 'nama as text')
            ->where('nama', 'like', '%' . $request->q . '%')
            ->where('is_active', true)
            ->where('jabatan_nama', 'not like', '%guru%')
            ->orWhere('nip_baru', 'like', '%' . $request->q . '%')
            ->where('is_active', true)
            ->where('jabatan_nama', 'not like', '%guru%')
            ->get();

        if ($data->count() > 0) {
            return response(array(
                'items' => $data->map(
                    function ($val) {
                        return array(
                            'id' => $val['id'],
                            'text' => $val['text'] . ' - ' . $val['id'],
                        );
                    },
                ),
                'count_filtered' => count($data),
            ), 200);
        }

        return response([], 200);
    }

    public function tppPegawai(Request $request)
    {

        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'bulan' => 'required',
                'tahun' => 'required',
            ]);


            if ($validator->fails()) {
                return response(array(
                    'success' => false,
                    'message' => 'Permintaan tidak dapat diproses'
                ), 400);
            }

            $user = Auth::user();

            $nip = $user->username;
            $bulan = $request->bulan;
            $tahun = $request->tahun;

            $profile_asn = json_decode($this->profileAsn($nip)->content())->data;

            $pegawai = array(
                'nip' => $nip,
                'unor_induk_id' => $profile_asn->UNOR_INDUK_ID,
                'unor_induk_nama' => $profile_asn->UNOR_INDUK_NAMA,
                'jabatan_nama' => $profile_asn->JABATAN_NAMA,
                'jenis_jabatan_id' => $profile_asn->JENIS_JABATAN_ID,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'tanggal' => $tahun . '-0' . $bulan,
            );

            $response = Http::withHeaders([
                'Secret-Key' => 'hmaoFUgrn/x+RqFsoyNnD7hgFpxzKamu9A6534nlrUU4rfrvqKGvigHShj5nYEGfeUGRjOv/OQIuuiPO7qXwJA==',
                'Authorization' => $request->header('Authorization'),
            ])->post('https://etpp.padang.go.id/api/tpp', $pegawai);

            $data = array(
                'tppAkhir' => 'Rp. 0',
                'tppBasic' => 'Rp. 0',
                'tppAktivitas' => 'Rp. 0',
                'tppKinerja' => 'Rp. 0',
                'tppKehadiran' => 'Rp. 0',
                'tppRealisasi' => 'Rp. 0',
                'capaianAktivitas' => 'Rp. 0',
                'capaianKinerja' => 'Rp. 0',
                'capaianKehadiran' => 'Rp. 0',
                'capaianRealisasi' => 'Rp. 0',
                'persentase' => '100',
                'persentaseAktivitas' => '50',
                'persentaseKinerja' => '15',
                'persentaseKehadiran' => '30',
                'persentaseRealisasi' => '5',
                'capaianPersentaseAktivitas' => '0',
                'capaianPersentaseKinerja' => '0',
                'capaianPersentaseKehadiran' => '0',
                'capaianPersentaseRealisasi' => '0',
                'capaianPersentasePotongan' => '0',
            );

            if ($response->successful()) {
                $data = collect(json_decode($response->body(), true)['data'])->map(function ($val) {
                    return array(
                        'tppAkhir' => 'Rp. ' . (string)number_format($val['capaian']['total']),
                        'tppBasic' => 'Rp. ' . (string)number_format($val['komponen']['jumlah_tpp']),
                        'tppAktivitas' => 'Rp. ' . (string)number_format($val['komponen']['jumlah_tpp'] * 0.5),
                        'tppKinerja' => 'Rp. ' . (string)number_format($val['komponen']['jumlah_tpp'] * 0.15),
                        'tppKehadiran' => 'Rp. ' . (string)number_format($val['komponen']['jumlah_tpp'] * 0.3),
                        'tppRealisasi' => 'Rp. ' . (string)number_format($val['komponen']['jumlah_tpp'] * 0.05),
                        'capaianAktivitas' => 'Rp. ' . (string)number_format($val['capaian']['berdasarkan_aktivitas_harian']),
                        'capaianKinerja' => 'Rp. ' . (string)number_format($val['capaian']['berdasarkan_kinerja']),
                        'capaianKehadiran' => 'Rp. ' . (string)number_format($val['capaian']['berdasarkan_kehadiran']),
                        'capaianRealisasi' => 'Rp. ' . (string)number_format($val['capaian']['berdasarkan_realisasi_opd']),
                        'persentase' => '100',
                        'persentaseAktivitas' => '50',
                        'persentaseKinerja' => '15',
                        'persentaseKehadiran' => '30',
                        'persentaseRealisasi' => '5',
                        'capaianPersentaseAktivitas' => (string)number_format($val['persentase']['berdasarkan_aktivitas_harian'], 2),
                        'capaianPersentaseKinerja' => (string)number_format($val['persentase']['berdasarkan_kinerja'], 2),
                        'capaianPersentaseKehadiran' => (string)number_format($val['persentase']['berdasarkan_kehadiran'], 2),
                        'capaianPersentaseRealisasi' => (string)number_format($val['persentase']['berdasarkan_realisasi_opd'], 2),
                        'capaianPersentasePotongan' => (string)number_format($val['persentase']['potongan'], 2),
                        // 'persentase_kinerja' => '70',
                        // 'persentase_presensi' => '30',
                        // 'persentase_aktivitas' => '0',
                        // 'tpp_kinerja' => (string)round($val['komponen']['berdasarkan_e_kinerja'], 2),
                        // 'tpp_presensi' => (string)$val['komponen']['berdasarkan_disiplin_kerja'],
                        // 'tpp_aktivitas' => (string)$val['komponen']['berdasarkan_aktivitas_harian'],
                        // 'tpp_aktivitas' => '0',
                        // 'nilai_skp' => $val['potongan']['ekinerja']['nilai_skp'] == '' ? '-' : $val['potongan']['ekinerja']['nilai_skp'],
                        // 'nilai_aktivitas_harian' => $val['potongan']['aktivitas_harian']['nilai'] == '' ? '-' : (string)$val['potongan']['aktivitas_harian']['nilai'],
                        // 'potongan_e_kinerja' => (string)$val['potongan']['ekinerja']['potongan_e_kinerja'],
                        // 'potongan_presensi' => (string)$val['potongan']['presensi']['potongan_disiplin_kerja'],
                        // 'potongan_aktivitas_harian' => (string)$val['potongan']['aktivitas_harian']['potongan'],
                        // 'jumlah_terlambat_30_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['30_menit']['jumlah'],
                        // 'jumlah_terlambat_31_60_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['31-60_menit']['jumlah'],
                        // 'jumlah_terlambat_61_90_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['61-90_menit']['jumlah'],
                        // 'jumlah_pulang_cepat_30_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['30_menit']['jumlah'],
                        // 'jumlah_pulang_cepat_31_60_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['31-60_menit']['jumlah'],
                        // 'jumlah_pulang_cepat_61_90_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['61-90_menit']['jumlah'],
                        // 'jumlah_tanpa_keterangan' => (string)$val['potongan']['presensi']['tanpa_keterangan']['jumlah'],
                        // 'tanggal_terlambat_30_menit' => count($val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['30_menit']['tanggal']) > 0 ? implode(',', $val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['30_menit']['tanggal']) : '-',
                        // 'tanggal_terlambat_31_60_menit' => count($val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['31-60_menit']['tanggal']) > 0 ? implode(',', $val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['31-60_menit']['tanggal']) : '-',
                        // 'tanggal_terlambat_61_90_menit' => count($val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['61-90_menit']['tanggal']) > 0 ? implode(',', $val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['61-90_menit']['tanggal']) : '-',
                        // 'tanggal_pulang_cepat_30_menit' => count($val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['30_menit']['tanggal']) > 0 ? implode(',', $val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['30_menit']['tanggal']) : '-',
                        // 'tanggal_pulang_cepat_31_60_menit' => count($val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['31-60_menit']['tanggal']) > 0 ? implode(',', $val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['31-60_menit']['tanggal']) : '-',
                        // 'tanggal_pulang_cepat_61_90_menit' => count($val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['61-90_menit']['tanggal']) > 0 ? implode(',', $val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['61-90_menit']['tanggal']) : '-',
                        // 'tanggal_tanpa_keterangan' => count($val['potongan']['presensi']['tanpa_keterangan']['tanggal']) > 0 ? implode(',', $val['potongan']['presensi']['tanpa_keterangan']['tanggal']) : '-',
                        // 'potongan_terlambat_30_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['30_menit']['total'],
                        // 'potongan_terlambat_31_60_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['31-60_menit']['total'],
                        // 'potongan_terlambat_61_90_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['terlambat']['61-90_menit']['total'],
                        // 'potongan_pulang_cepat_30_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['30_menit']['total'],
                        // 'potongan_pulang_cepat_31_60_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['31-60_menit']['total'],
                        // 'potongan_pulang_cepat_61_90_menit' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['pulang_cepat']['61-90_menit']['total'],
                        // 'potongan_tanpa_keterangan' => (string)$val['potongan']['presensi']['tanpa_keterangan']['potongan'],
                        // 'potongan_terlambat_dan_pulang_cepat' => (string)$val['potongan']['presensi']['potongan_terlambat_dan_pulang_cepat']['potongan'],
                        // "iwp_1_persen" => (string)$val['potongan']['iwp_1%'],
                        // 'jumlah_penerimaan_kotor' => (string)$val['potongan']['jumlah_penerimaan_kotor'],
                        // 'penerimaan_kotor_beban_kerja' => (string)$val['potongan']['penerimaan_kotor_beban_kerja'],
                        // 'penerimaan_kotor_kondisi_kerja' => (string)$val['potongan']['penerimaan_kotor_kondisi_kerja'],
                        // 'penerimaan_kotor_prestasi_kerja' => (string)$val['potongan']['penerimaan_kotor_prestasi_kerja'],
                        // 'penerimaan_kotor_kelangkaan_profesi' => (string)$val['potongan']['penerimaan_kotor_kelangkaan_profesi'],
                        // 'pph_beban_kerja' => (string)$val['potongan']['pph_beban_kerja'],
                        // 'pph_kondisi_kerja' => (string)$val['potongan']['pph_kondisi_kerja'],
                        // 'pph_prestasi_kerja' => (string)$val['potongan']['pph_prestasi_kerja'],
                        // 'pph_kelangkaan_profesi' => (string)$val['potongan']['pph_kelangkaan_profesi'],
                        // 'jumlah_pph_21' => (string)$val['potongan']['jumlah_pph_21'],
                        // 'total_potongan' => (string)$val['potongan']['total_potongan'],
                        // 'jumlah_ditransfer' => (string)$val['potongan']['jumlah_ditransfer'],
                    );
                })->first();
            }

            return response(array(
                'message' => 'Data tpp berhasil ditemukan',
                'data' => $data,
            ), 200);
        } else {
            return response()->json([
                'message' => 'unauthorized',
            ], 400);
        }
    }

    public function getDataAsisten()
    {
        $data = null;
        $status_code = 404;
        $message = 'Data tidak ditemukan';

        $result = DataUtama::select('nip_baru', 'nama', 'jabatan_struktural_nama', 'gelar_depan', 'gelar_belakang')->where('jabatan_struktural_nama', 'like', '%asisten%')->get();

        if (!empty($result)) {
            $data = $result->map(function ($value) {
                $gelar_depan = $value['gelar_depan'];
                $gelar_belakang = $value['gelar_belakang'];
                $nama = $gelar_depan . '. ' . ucwords(strtolower($value['nama'])) . ', ' . $gelar_belakang;
                return array(
                    'nip' => $value['nip_baru'],
                    'nama' => str_replace('..', '.', $nama),
                    'jabatan' => str_replace('Dan', 'dan', ucwords(strtolower($value['jabatan_struktural_nama']))),
                );
            });
            $message = 'Data ditemukan.';
            $status_code = 200;
        }

        return response(array(
            'message' => $message,
            'data' => $data
        ), $status_code);
    }
}
