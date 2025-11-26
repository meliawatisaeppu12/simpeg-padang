<?php

namespace Modules\Api\Http\Controllers;

use App\Models\SSO\AccessLevel;
use App\Models\SSO\Disposisi;
use App\Models\SSO\SuratMasuk;
use App\Models\SSO\TujuanSurat;
use App\Models\V2\DataNonAsn;
use App\Models\V2\DataUtama;
use App\Models\V2\MasterOrganisasi;
use App\Models\V2\NonAsnAccess;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\V2\PersonalAccess;
use App\Models\V2\PositionAccess;
use Exception;
use Illuminate\Support\Collection;

class ApiSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $username = $user->username;

        if ($user->jenis_kepegawaian == 'asn') {

            $data_utama = DataUtama::select('id', 'jabatan_struktural_id', 'jabatan_fungsional_id', 'jabatan_fungsional_umum_id')->where('nip_baru', $username)->first();

            $jabatan_struktural_id = $data_utama->jabatan_struktural_id;
            $jabatan_fungsional_id = $data_utama->jabatan_fungsional_id;
            $jabatan_fungsional_umum_id = $data_utama->jabatan_fungsional_umum_id;

            $jabatan_id = !empty($jabatan_struktural_id) ? $jabatan_struktural_id : (!empty($jabatan_fungsional_id) ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);

            $personal_access = PersonalAccess::select('access_id', 'access_status')
                ->where('pns_id', $data_utama->id)
                ->where('access_status', true)
                ->whereHas('access_data', function ($query) {
                    $query->where('kode_akses', 'input_surat')->orWhere('kode_akses', 'disposisi_surat');
                })
                ->with('access_data')
                ->get()->toArray();

            $position_access = PositionAccess::select('access_id', 'access_status')
                ->where('jabatan_id', $jabatan_id)
                ->where('access_status', true)
                ->whereHas('access_data', function ($query) {
                    $query->where('kode_akses', 'input_surat')->orWhere('kode_akses', 'disposisi_surat');
                })
                ->with('access_data')
                ->get()->toArray();

            $merged_access = collect($personal_access)->merge(collect($position_access));
        } else {
            $jabatan_id = $user->nonAsn->jabatan_id;
            $non_asn_access = NonAsnAccess::select('access_id', 'access_status')
                ->where('username', $user->username)
                ->where('access_status', true)
                ->whereHas('access_data', function ($query) {
                    $query->where('kode_akses', 'input_surat')->orWhere('kode_akses', 'disposisi_surat');
                })
                ->with('access_data')
                ->get()->toArray();
            $merged_access = collect($non_asn_access);
        }

        if ($merged_access->count() == 0) {
            return response()->json([
                'message' => 'Akses data surat tidak diizinkan.'
            ], 403);
        }

        $access_level = AccessLevel::where('jabatan_id', $jabatan_id)->first();

        $is_operator = empty($access_level) ? true : false;

        $data_surat = new Collection();

        if ($is_operator) {
            $data_surat = SuratMasuk::where('operator', $username)->with(['tujuan', 'disposisi', 'asisten' => function ($query) {
                $query->select(
                    'nip_baru',
                    'nama',
                    'gelar_depan',
                    'gelar_belakang',
                    'jabatan_struktural_nama',
                );
            }])->orderBy('id', 'DESC')->get();
        } else {
            if ($access_level->level == 1) {
                $data_surat = SuratMasuk::where('asisten_nip', $username)->with(['tujuan', 'disposisi', 'asisten' => function ($query) {
                    $query->select(
                        'nip_baru',
                        'nama',
                        'gelar_depan',
                        'gelar_belakang',
                        'jabatan_struktural_nama',
                    );
                }])->orderBy('id', 'DESC')->get();
            } else {
                $data_surat = SuratMasuk::with(['tujuan', 'disposisi', 'asisten' => function ($query) {
                    $query->select(
                        'nip_baru',
                        'nama',
                        'gelar_depan',
                        'gelar_belakang',
                        'jabatan_struktural_nama',
                    );
                }])->whereHas('disposisi', function ($query) use ($username) {
                    $query->where('to_user', $username);
                })->orderBy('id', 'DESC')->get();
            }
        }

        if ($data_surat->count() == 0) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $list = $data_surat->map(function ($val) use ($username) {
            $gda = $val['asisten']['gelar_depan'];
            $gba = $val['asisten']['gelar_belakang'];
            $na = str_replace('..', '.', $gda . '. ' . ucwords(strtolower($val['asisten']['nama'])) . ', ' . $gba);
            $ja = ucwords(strtolower($val['asisten']['jabatan_struktural_nama']));
            $disposisi = collect($val['disposisi'])->map(function ($val) {
                return array(
                    'id' => (string)$val['id'],
                    'nama' => (string)$val['from_user_nama'],
                    'jabatan' => (string)$val['from_user_jabatan'],
                    'telitiSaran' => (string)$val['teliti_saran'],
                    'toNama' => (string)$val['to_user_nama'],
                    'toJabatan' => (string)$val['to_user_jabatan'],
                    'toNip' => (string)$val['to_user'],
                    'tanggal' => (string)Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($val['created_at'])))->translatedFormat('j M Y'),
                    'jam' => (string)date('H:i', strtotime($val['created_at'])),
                );
            })->values();
            $disposisi_terakhir = collect($disposisi)->sortByDesc('id')->first();
            $status = 'Menunggu.';
            $currentUser = null;

            $tanggal_input = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($val['created_at'])))->translatedFormat('j M Y');
            $jam_input = date('H:i', strtotime($val['created_at']));

            if (empty($disposisi) || $disposisi == null || count($disposisi) == 0) {
                $currentUser = $val['asisten_nip'];
                if ($val['asisten_nip'] == $username) {
                    $status = ucfirst(strtolower($val['jenis'])) . ' menunggu disposisi ' . $na . ' (' . $ja . ')';
                } else {
                    $status = ucfirst(strtolower($val['jenis'])) . ' sudah diteruskan ke ' . $na . ' (' . $ja . ')';
                }
            } else {
                $status = ucfirst(strtolower($val['jenis'])) . ' sudah diteruskan ke ' . ucwords(strtolower($disposisi_terakhir['toNama'])) . ' (' . $disposisi_terakhir['toJabatan'] . ')';
                $currentUser = $disposisi_terakhir['toNip'];
            }

            $tujuan = collect($val['tujuan'])->map(function ($val) {
                return array(
                    'id' => (string)$val['id'],
                    'penerimaId' => $val['penerima_id'],
                    'penerimaNama' => $val['penerima_nama'],
                );
            });

            $newDisposisi = collect([
                array(
                    'id' => 99999,
                    'telitiSaran' => $status,
                    'tanggal' => '',
                    'jam' => '',
                ),
                array(
                    'id' => 0,
                    'telitiSaran' => ucfirst(strtolower($val['jenis'])) . ' telah diinput.',
                    'tanggal' => (string)$tanggal_input,
                    'jam' => (string)$jam_input,
                ),
            ]);

            return array(
                'id' => (string)$val['id'],
                'pengirim' => ucfirst(strtolower($val['jenis'])) . ' ' . ucwords(strtolower($val['pengirim_nama'])),
                'disposisi' => $status,
                'currentUser' => $currentUser,
                'tanggal' => $tanggal_input,
                'jam' => $jam_input,
                'kodeSurat' => $val['kode_surat'],
                'detail' => array(
                    'id' => (string)$val['id'],
                    'pengirim' => ucwords(strtolower($val['pengirim_nama'])),
                    'jenis' => $val['jenis'],
                    'status' => $status,
                    'disposisi' => collect($disposisi)->merge($newDisposisi)->sortByDesc('id')->values(),
                    'perihal' => ucwords(strtolower($val['perihal'])),
                    'tujuan' => $tujuan,
                    'currentUser' => $currentUser,
                    'tanggal' => $tanggal_input,
                    'jam' => $jam_input,
                    'path' => url('/surat/file/' . $val['file_path']),
                ),
            );
        });

        return response()->json([
            'message' => 'Data ditemukan',
            'data' =>  $list
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function dashboardList()
    {
        $data_surat = SuratMasuk::with(['tujuan', 'disposisi' => function ($query) {
            $query->select('id', 'surat_masuk_id', 'to_user_nama', 'to_user_jabatan', 'created_at')->orderBy('id', 'DESC')->first();
        }, 'asisten' => function ($query) {
            $query->select(
                'nip_baru',
                'nama',
                'gelar_depan',
                'gelar_belakang',
                'jabatan_struktural_nama',
            );
        }])->where('created_at', '<', '2025-07-01 00:00:00')->orderBy('id', 'DESC')->get();

        if ($data_surat->count() == 0) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $surat = $data_surat->map(function ($val) {
            $gda = $val['asisten']['gelar_depan'];
            $gba = $val['asisten']['gelar_belakang'];
            $na = str_replace('..', '.', $gda . '. ' . ucwords(strtolower($val['asisten']['nama'])) . ', ' . $gba);
            $ja = ucwords(strtolower($val['asisten']['jabatan_struktural_nama']));
            $tujuan = collect($val['tujuan'])->pluck('penerima_nama')->toArray();
            $disposisi = $val['disposisi'];
            $status = 'Menunggu.';

            $tanggal_input = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($val['created_at'])))->translatedFormat('j F Y');
            $tanggal_diteruskan = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($val['created_at'])))->translatedFormat('j F Y');
            $jam_input = date('H:i', strtotime($val['created_at']));
            $jam_diteruskan = date('H:i', strtotime($val['created_at']));

            if (count($disposisi) == 0) {
                $status = ucfirst(strtolower($val['jenis'])) . ' sudah diteruskan ke ' . $na . ' (' . $ja . ')';
            } else {
                $status = ucfirst(strtolower($val['jenis'])) . ' sudah diteruskan ke ' . ucwords(strtolower($val['disposisi'][0]['to_user_nama'])) . ' (' . ucwords(strtolower($val['disposisi'][0]['to_user_jabatan'])) . ')';
                $tanggal_diteruskan = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($val['disposisi'][0]['created_at'])))->translatedFormat('j F Y');
                $jam_diteruskan = date('H:i', strtotime($val['disposisi'][0]['created_at']));
            }

            return array(
                'id' => $val['id'],
                'jenis' => ucfirst(strtolower($val['jenis'])),
                'pengirim' => ucwords(strtolower($val['pengirim_nama'])),
                'tujuan' => $tujuan,
                'status' => $status,
                'tanggal_input' => $tanggal_input,
                'tanggal_diteruskan' => $tanggal_diteruskan,
                'jam_input' => $jam_input,
                'jam_diteruskan' => $jam_diteruskan,
            );
        });

        return response()->json(['message' => 'List surat', 'data' => $surat], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function dashboardMatrix()
    {
        return response()->json(['message' => 'Coming soon'], 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pengirim_id' => 'required|string',
            'tujuan_id' => 'required|array',
            'asisten' => 'required|string',
            'pengirim_nama' => 'required|string',
            'jenis' => 'required|string',
            'perihal' => 'required|string',
            'file' => 'required|mimes:pdf|max:20480',
        ], [], [
            'pengirim_id' => 'Kolom Dari',
            'pengirim_nama' => 'Nama pengirim',
            'tujuan_id' => 'Tujuan surat',
            'asisten' => 'Asisten',
            'jenis' => 'Jenis surat',
            'perihal' => 'Perihal',
            'file' => 'File lampiran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $path = $request->pengirim_id . '/' . str_replace(' ', '_', strtolower($request->pengirim_nama)) . '/' . time() . '.' . $request->file('file')->getClientOriginalExtension();

        $file_status = Storage::disk('surat')->put($path, file_get_contents($request->file('file')));
        $surat_masuk = null;
        $kode_surat = date('m-d') . '-' . time();

        if ($file_status) {
            $surat_masuk = new SuratMasuk();
            $surat_masuk->pengirim_id = $request->pengirim_id;
            $surat_masuk->pengirim_nama = $request->pengirim_nama;
            $surat_masuk->asisten_nip = $request->asisten;
            $surat_masuk->jenis = $request->jenis;
            $surat_masuk->perihal = $request->perihal;
            $surat_masuk->file_ori_nama = $request->file('file')->getClientOriginalName();
            $surat_masuk->file_path = $path;
            $surat_masuk->operator = Auth::user()->username;
            $surat_masuk->kode_surat = $kode_surat;
            $surat_masuk->save();

            $data = null;

            $organisasi = MasterOrganisasi::get();
            $surat_masuk_id = $surat_masuk->id;

            foreach ($request->tujuan_id as $x) {
                $data[] = [
                    'surat_masuk_id' => $surat_masuk_id,
                    'penerima_id' => $x,
                    'penerima_nama' => $organisasi->where('unor_induk_id', $x)->first()->unor_induk_nama,
                ];
            }

            TujuanSurat::insert($data);

            $asisten = $surat_masuk->asisten()->first();
            $gd = str_replace('..', '.', $asisten->gelar_depan . '. ');
            $gb = $asisten->gelar_belakang;
            $nama = $gd . ucwords(strtolower($asisten->nama)) . ', ' . $gb;
            $jabatan = ' (' . $asisten->jabatan_struktural_nama . ')';
            $status = ucfirst(strtolower($surat_masuk->jenis)) . ' sudah diteruskan ke ' . $nama . ' (' . $jabatan . ')';
            $tujuan = collect($surat_masuk->tujuan)->map(function ($val) {
                return array(
                    'id' => (string)$val['id'],
                    'penerimaId' => $val['penerima_id'],
                    'penerimaNama' => $val['penerima_nama'],
                );
            });
            $tanggal_input = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($surat_masuk->created_at)))->translatedFormat('j M Y');
            $jam_input = date('H:i', strtotime($surat_masuk->created_at));
            $newDisposisi = collect([
                array(
                    'id' => 99999,
                    'telitiSaran' => $status,
                    'tanggal' => '',
                    'jam' => '',
                ),
                array(
                    'id' => 0,
                    'telitiSaran' => ucfirst(strtolower($surat_masuk->jenis)) . ' telah diinput.',
                    'tanggal' => (string)$tanggal_input,
                    'jam' => (string)$jam_input,
                ),
            ]);
            return response()->json([
                'message' => 'Surat berhasil disimpan',
                'data' => array(
                    'id' => (string)$surat_masuk_id,
                    'pengirim' => ucfirst(strtolower($surat_masuk->jenis)) . ' ' . ucwords(strtolower($surat_masuk->pengirim_nama)),
                    'disposisi' => ucfirst(strtolower($surat_masuk->jenis)) . ' sudah diteruskan ke ' . $nama . $jabatan,
                    'tanggal' => Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($surat_masuk->created_at)))->translatedFormat('j M Y'),
                    'jam' => date('H:i', strtotime($surat_masuk->created_at)),
                    'currentUser' => $request->asisten,
                    'kodeSurat' => $kode_surat,
                    'detail' => array(
                        'id' => (string)$surat_masuk->id,
                        'pengirim' => ucfirst(strtolower($surat_masuk->jenis)) . ' ' . ucwords(strtolower($surat_masuk->pengirim_nama)),
                        'jenis' => $surat_masuk->jenis,
                        'status' => $status,
                        'disposisi' => $newDisposisi,
                        'perihal' => ucwords(strtolower($surat_masuk->perihal)),
                        'tujuan' => $tujuan,
                        'currentUser' => $request->asisten,
                        'tanggal' => $tanggal_input,
                        'jam' => $jam_input,
                        'path' => url('/surat/file/' . $surat_masuk->file_path),
                    ),
                ),
            ], 201);
        }


        return response()->json([
            'message' => 'Permintaan tidak dapat diproses.'
        ], 400);
    }

    public function disposisiBerikutnya($suratId)
    {
        try {
            $access_level = $this->getLevel();

            $jabatan_berikutnya = AccessLevel::select('id', 'jabatan_id', 'level')
                ->where('access_id', 11)
                ->where('level', $access_level + 1)
                ->orderBy('id', 'ASC')
                ->with('dataUtama')
                ->first();

            $lastLevel = AccessLevel::select('level')
                ->where('access_id', 11)
                ->orderBy('level', 'DESC')
                ->first()->level;

            $lastLevel = AccessLevel::select('level')
                ->where('access_id', 11)
                ->orderBy('level', 'DESC')
                ->first()->level;



            if ($lastLevel == $access_level) {
                $data = null;
                $opd_penerima = TujuanSurat::select('penerima_id', 'penerima_nama')->where('surat_masuk_id', $suratId)->get();
            } else {
                if (in_array($jabatan_berikutnya->jabatan_id, ['walikota', 'wakilwalikota'])) {
                    $data = DataNonAsn::where('jabatan_id', $jabatan_berikutnya->jabatan_id)->orderBy('id', 'DESC')->first();
                    $nip = $data->username;
                    $nama = ucwords(strtolower($data->nama));
                    $gelar_depan = '';
                    $gelar_belakang = '';
                    $jabatan = $data->jabatan;
                } else {
                    $nip = $jabatan_berikutnya->dataUtama->nip_baru;
                    $nama = ucwords(strtolower($jabatan_berikutnya->dataUtama->nama));
                    $gelar_depan = $jabatan_berikutnya->dataUtama->gelar_depan . ' ';
                    $gelar_belakang = ' ' . $jabatan_berikutnya->dataUtama->gelar_belakang;
                    $jabatan = $jabatan_berikutnya->dataUtama->jabatan_struktural_nama;
                }

                $data = array(
                    'nip' => $nip,
                    'nama' => $gelar_depan . $nama . $gelar_belakang,
                    'jabatan' => ucwords(strtolower($jabatan)),
                );
                $opd_penerima = null;
            }

            return response()->json([
                'message' => 'Data berhasil ditemukan',
                'data' => $data,
                'opd_penerima' => $opd_penerima
            ], 200);
        } catch (Exception $exception) {
            $rnd = rand(1, 10000);
            Log::error('Code: ' . $rnd . ' | Error: ' . $exception);
            return response()->json([
                'code' => $rnd,
            ], 500);
        }
    }

    public function storeDisposisi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surat_masuk_id' => 'required',
            'from_user' => 'required',
            'teliti_saran' => 'required',
            'to_user' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'message' => $validator->errors()->first(),
            ), 400);
        }

        $non_asn_username = DataNonAsn::select('username')->get()->pluck('username')->toArray();

        if (in_array($request->from_user, $non_asn_username)) {
            $pengirim_type = 'non_asn';
        } else {
            $pengirim_type = 'asn';
        }

        if (in_array($request->to_user, $non_asn_username)) {
            $penerima_type = 'non_asn';
        } else {
            $penerima_type = 'asn';
        }

        $pengirim = $this->getPelaksana($pengirim_type, $request->from_user);
        $penerima = $this->getPelaksana($penerima_type, $request->to_user);

        // return response()->json(array(
        //     'message' => array(
        //         'type' => $penerima_type,
        //         'to_user' => $request->to_user,
        //         'penerima' => $penerima
        //     )
        // ), 400);

        $surat_id = $request->surat_masuk_id;
        $telisi_saran = $request->teliti_saran;

        $check = Disposisi::where('surat_masuk_id', $surat_id)->where('from_user', $pengirim->getIdentifier())->count();

        if($check == 0) {
            $disposisi = new Disposisi();
            $disposisi->surat_masuk_id = $surat_id;
            $disposisi->from_user = $pengirim->getIdentifier();
            $disposisi->from_user_nama = ucwords($pengirim->getNama());
            $disposisi->from_user_jabatan = ucwords($pengirim->getJabatan());
            $disposisi->from_user_tipe = $pengirim_type;
            $disposisi->teliti_saran = $telisi_saran;
            $disposisi->to_user = $penerima->getIdentifier();
            $disposisi->to_user_nama = ucwords($penerima->getNama());
            $disposisi->to_user_jabatan = ucwords($penerima->getJabatan());
            $disposisi->to_user_tipe = $penerima_type;
    
            if ($disposisi->save()) {
                return response()->json(array(
                    'message' => 'Berhasil.'
                ), 201);
            }
        }else{
            return response()->json(array(
                'message' => 'Berhasil.'
            ), 201);
        }
        

        return response()->json(array(
            'message' => 'Data gagal diproses.'
        ), 400);
    }

    public function cekSurat($kode_surat)
    {
        $data_surat = SuratMasuk::where('kode_surat', $kode_surat)->with(['tujuan', 'disposisi', 'asisten' => function ($query) {
            $query->select(
                'nip_baru',
                'nama',
                'gelar_depan',
                'gelar_belakang',
                'jabatan_struktural_nama',
            );
        }])->orderBy('id', 'DESC')->get();


        if ($data_surat->count() == 0) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $list = $data_surat->map(function ($val) {
            $gda = $val['asisten']['gelar_depan'];
            $gba = $val['asisten']['gelar_belakang'];
            $na = str_replace('..', '.', $gda . '. ' . ucwords(strtolower($val['asisten']['nama'])) . ', ' . $gba);
            $ja = ucwords(strtolower($val['asisten']['jabatan_struktural_nama']));
            $disposisi = collect($val['disposisi'])->map(function ($val) {
                return array(
                    'id' => (string)$val['id'],
                    'nama' => (string)$val['from_user_nama'],
                    'jabatan' => (string)$val['from_user_jabatan'],
                    'telitiSaran' => (string)$val['teliti_saran'],
                    'toNama' => (string)$val['to_user_nama'],
                    'toJabatan' => (string)$val['to_user_jabatan'],
                    'toNip' => (string)$val['to_user'],
                    'tanggal' => (string)Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($val['created_at'])))->translatedFormat('j M Y'),
                    'jam' => (string)date('H:i', strtotime($val['created_at'])),
                );
            })->values();
            $disposisi_terakhir = collect($disposisi)->sortByDesc('id')->first();
            $status = 'Menunggu.';
            $currentUser = null;

            $tanggal_input = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($val['created_at'])))->translatedFormat('j M Y');
            $jam_input = date('H:i', strtotime($val['created_at']));

            if (empty($disposisi) || $disposisi == null || count($disposisi) == 0) {
                $currentUser = $val['asisten_nip'];
                $status = ucfirst(strtolower($val['jenis'])) . ' menunggu disposisi ' . $na . ' (' . $ja . ')';
            } else {
                $status = ucfirst(strtolower($val['jenis'])) . ' sudah diteruskan ke ' . ucwords(strtolower($disposisi_terakhir['toNama'])) . ' (' . $disposisi_terakhir['toJabatan'] . ')';
                $currentUser = $disposisi_terakhir['toNip'];
            }

            $tujuan = collect($val['tujuan'])->map(function ($val) {
                return array(
                    'id' => (string)$val['id'],
                    'penerimaId' => $val['penerima_id'],
                    'penerimaNama' => $val['penerima_nama'],
                );
            });

            $newDisposisi = collect([
                array(
                    'id' => 99999,
                    'telitiSaran' => $status,
                    'tanggal' => '',
                    'jam' => '',
                ),
                array(
                    'id' => 0,
                    'telitiSaran' => ucfirst(strtolower($val['jenis'])) . ' telah diinput.',
                    'tanggal' => (string)$tanggal_input,
                    'jam' => (string)$jam_input,
                ),
            ]);

            return array(
                'id' => (string)$val['id'],
                'pengirim' => ucfirst(strtolower($val['jenis'])) . ' ' . ucwords(strtolower($val['pengirim_nama'])),
                'disposisi' => $status,
                'tanggalInput' => $tanggal_input,
                'jamInput' => $jam_input,
                'kodeSurat' => $val['kode_surat'],
                'detail' => array(
                    'id' => (string)$val['id'],
                    'pengirim' => ucwords(strtolower($val['pengirim_nama'])),
                    'jenis' => $val['jenis'],
                    'status' => $status,
                    'disposisi' => collect($disposisi)->merge($newDisposisi)->sortByDesc('id')->values(),
                    'perihal' => ucwords(strtolower($val['perihal'])),
                    'tujuan' => $tujuan,
                    'currentUser' => $currentUser,
                    'tanggal' => $tanggal_input,
                    'jam' => $jam_input,
                    'path' => url('/surat/file/' . $val['file_path']),
                ),
            );
        });

        return response()->json([
            'message' => 'Data ditemukan',
            'data' =>  $list
        ], 200);
    }

    private function getPelaksana(string $type, String $username)
    {
        if ($type === 'asn') {
            return DataUtama::where('nip_baru', $username)->first();
        } else {
            return DataNonAsn::where('username', $username)->first();
        }
    }

    private function getLevel()
    {
        $user = Auth::user();

        if ($user->jenis_kepegawaian === 'asn') {
            return Auth::user()->v2Profile()
                ->with('accessLevel')
                ->first()
                ->accessLevel
                ->level;
        } else {
            return Auth::user()->dataNonAsn()
                ->with('accessLevel')
                ->first()
                ->accessLevel
                ->level;
        }
    }
}
