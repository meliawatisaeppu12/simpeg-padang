<?php

namespace Modules\Api\Http\Controllers;

use App\Models\BerkasPegawai;
use App\Models\Layanan\TransBerkas;
use App\Models\Layanan\TransLayanan;
use App\Models\Layanan\JenisLayanan;
use App\Models\Layanan\PeriodeLayanan;
use App\Models\Layanan\Layanan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Layanan\AksesLayanan;
use App\Models\Layanan\ProsesLayanan;
use App\Models\V2\DataUtama;
use App\Models\V2\KelompokAbsen;
use App\Models\V2\RiwayatHukdis;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;
use DatePeriod;
use DateInterval;

class ApiLayananController extends Controller
{
    public function cekPeriodeLayanan($kode_layanan)
    {
        $status_kode = 404;

        $success = false;

        $message = 'Layanan belum tersedia.';

        $data = null;

        $periode_tanggal = [];

        $periode_aktif = false;

        $layanan = Layanan::select('id')->where('kode_layanan', $kode_layanan)->first();

        if (empty($layanan)) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ), 404);
        }

        $periode_layanan = PeriodeLayanan::select('id', 'nama_periode', 'id_layanan', 'tanggal_awal', 'tanggal_akhir')

            ->where('id_layanan', $layanan->id)

            ->get();

        if ($periode_layanan->count() > 0) {


            foreach ($periode_layanan as $x) {

                $tanggal_awal = date('Y-m-d', strtotime($x['tanggal_awal']));

                $tanggal_akhir = date('Y-m-d', strtotime($x['tanggal_akhir'] . '+1 day'));

                $periode = new DatePeriod(
                    new DateTime($tanggal_awal),
                    new DateInterval('P1D'),
                    new DateTime($tanggal_akhir)
                );

                foreach ($periode as $value) {

                    $periode_tanggal[] = $value->format('Y-m-d');
                }
            }

            if (in_array(Date('Y-m-d', strtotime(now())), $periode_tanggal)) {
                $periode_aktif = true;
            }

            $status_kode = 200;

            $success = true;

            $message = 'Layanan tersedia.';

            $data = $periode_layanan;
        }

        return response(array(

            'success' => $success,

            'message' => $message,

            'periode_aktif' => $periode_aktif,

            'data' => $data,

        ), $status_kode);
    }

    public function getJenisLayanan($kode_layanan)
    {
        $layanan = Layanan::select('id', 'nama_layanan', 'kode_layanan', 'keterangan')

            ->where('kode_layanan', $kode_layanan)


            ->with('jenisLayanan', function ($query) {

                $query->select('id', 'nama_jenis', 'id_layanan');
            })->first();

        $status_kode = 404;

        $success = false;

        $message = 'Layanan belum tersedia.';

        $data = null;

        if (count($layanan['jenisLayanan']) > 0) {

            $status_kode = 200;

            $success = true;

            $message = 'Jenis layanan tersedia.';

            $data = $layanan['jenisLayanan'];
        }

        return response(array(

            'success' => $success,

            'message' => $message,

            'data' => $data,

        ), $status_kode);
    }

    public function getBerkasLayanan($id_jenis_layanan)
    {
        $jenis_layanan = JenisLayanan::select('id', 'nama_jenis', 'id_layanan')

            ->where('id', $id_jenis_layanan)

            ->with('berkasLayanan', function ($query) {

                $query->select('m_berkas.id', 'nama_berkas', 'tb_berkas_layanan.catatan', 'ekstensi', 'ukuran_maksimal', 'keterangan', 'file_baru', 'nama_form');
            })->first();

        $status_kode = 404;

        $success = false;

        $message = 'Layanan belum tersedia.';

        $data = null;

        if (count($jenis_layanan['berkasLayanan']) > 0) {

            $status_kode = 200;

            $success = true;

            $message = 'Berkas layanan tersedia.';

            $data = $jenis_layanan['berkasLayanan'];
        }

        return response(array(

            'success' => $success,

            'message' => $message,

            'data' => $data,

        ), $status_kode);
    }

    public function getDataKota()
    {
        $kecamatan = Kecamatan::get();

        $data_kelurahan = array();

        foreach ($kecamatan as $x) {

            $id = $x->id;

            $response = Http::get('https://raw.github.com/rahayuabadi/api-wilayah-indonesia/master/static/api/villages/' . $id . '.json');

            if ($response->successful()) {

                $response_body = json_decode($response->body());

                foreach ($response_body as $y) {

                    $data = array(

                        'kelurahan_id' => $y->id,

                        'district_id' => $y->district_id,

                        'name' => $y->name,

                    );

                    array_push($data_kelurahan, $data);
                }
            }
        }

        $status = [];

        foreach (array_chunk($data_kelurahan, 10000) as $t) {
            $status[] = Kelurahan::insert($t);
        }


        return $status;
    }

    public function getDraftLayanan($nip, $id_jenis_layanan)
    {

        $success = false;

        $message = 'Draft tidak ditemukan.';

        $data = null;

        $status_kode = 404;

        $draftLayanan = TransLayanan::where('nip', $nip)
            ->where('id_jenis_layanan', $id_jenis_layanan)
            ->where('status', 0)
            ->with('berkasDraft', function ($query) {
                $query->select('tb_berkas_pegawai.id', 'nip', 'id_berkas', 'nama_berkas', 'nama_berkas_asli');
            })->first();

        if (!empty($draftLayanan)) {

            $status_kode = 200;

            $success = true;

            $message = 'Draft ditemukan';

            $data = $draftLayanan;
        }

        return response(array(

            'success' => $success,

            'message' => $message,

            'data' => $data,
        ), $status_kode);
    }

    public function simpanDraftBerkas(Request $request)
    {

        $success = false;

        $message = 'Berkas gagal disimpan.';

        $status_kode = 400;

        $validator = Validator::make($request->all(), [
            'berkas' => 'required|mimetypes:application/pdf|max:2000'
        ]);

        if ($validator->fails()) {
            return response(array(
                'success' => $success,
                'message' => $validator->errors()->first(),
                'data' => null,
            ), $status_kode);
        }

        $id_jenis_layanan = $request->id_jenis_layanan;

        $nip = $request->nip;

        $kode_draft = $request->kode_draft;

        $file = $request->file('berkas');

        $id_berkas = $request->id_berkas;

        $nama_file = time() . '.' . $file->getClientOriginalExtension();

        $draftLayanan = TransLayanan::select('id', 'id_jenis_layanan', 'nip', 'kode_draft')
            ->where('nip', $nip)
            ->where('kode_draft', $kode_draft)
            ->where('status', 0)
            ->first();

        if (empty($draftLayanan)) {
            $draftLayanan = new TransLayanan();
            $draftLayanan->id_jenis_layanan = $id_jenis_layanan;
            $draftLayanan->nip = $nip;
            $draftLayanan->kode_draft = $kode_draft;
            if ($draftLayanan->save()) {

                $file->move(base_path("/storage/app/public/berkas/") . $nip . '/' . $id_berkas, $nama_file);

                $berkasPegawai = new BerkasPegawai();
                $berkasPegawai->nip = $nip;
                $berkasPegawai->id_berkas = $id_berkas;
                $berkasPegawai->nama_berkas = $nama_file;
                $berkasPegawai->nama_berkas_asli = $file->getClientOriginalName();
                $berkasPegawai->save();

                $draftBerkas = new TransBerkas();
                $draftBerkas->id_trans_layanan = $draftLayanan->id;
                $draftBerkas->id_berkas_pegawai = $berkasPegawai->id;
                $draftBerkas->save();
                $success = true;
                $status_kode = 200;
                $message = 'Berkas berhasil disimpan.';
            }
        } else {
            $file->move(base_path("/storage/app/public/berkas/") . $nip . '/' .  $id_berkas, $nama_file);

            $berkasPegawai = new BerkasPegawai();
            $berkasPegawai->nip = $nip;
            $berkasPegawai->id_berkas = $id_berkas;
            $berkasPegawai->nama_berkas = $nama_file;
            $berkasPegawai->nama_berkas_asli = $file->getClientOriginalName();
            $berkasPegawai->save();

            $draftBerkas = new TransBerkas();
            $draftBerkas->id_trans_layanan = $draftLayanan->id;
            $draftBerkas->id_berkas_pegawai = $berkasPegawai->id;
            $draftBerkas->save();
            $success = true;
            $status_kode = 200;
            $message = 'Berkas berhasil disimpan.';
        }

        return response(array(
            'success' => $success,
            'message' => $message,
        ), $status_kode);
    }

    public function hapusDraft($id_draft)
    {
        $success = false;

        $message = 'Berkas gagal disimpan.';

        $status_kode = 400;

        $draft_layanan = TransLayanan::where('id', $id_draft)->delete();

        $draft_berkas = TransBerkas::where('id_trans_layanan', $id_draft)->delete();

        if ($draft_layanan && $draft_berkas) {

            $success = true;

            $message = 'Draft berhasil dihapus.';

            $status_kode = 200;
        }

        return response(array(
            'success' => $success,
            'message' => $message,
        ), $status_kode);
    }

    public function kirimLayanan(Request $request)
    {
        $success = false;

        $message = 'Terjadi kesalahan.';

        $status_kode = 400;

        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'kode_draft' => 'required',
            'level' => 'required',
        ]);

        if ($validator->fails()) {
            return response(array(
                'success' => $success,
                'message' => $message,
            ), $status_kode);
        }

        $trans_layanan = TransLayanan::select('id', 'id_jenis_layanan', 'nip', 'kode_draft', 'status')
            ->where('nip', $request->nip)
            ->where('kode_draft', $request->kode_draft)
            ->first();

        $data_utama = DataUtama::select('unor_id')->where('nip_baru', $request->nip)->first();

        $id_trans_layanan = $trans_layanan->id;

        $id_jenis_layanan = $trans_layanan->id_jenis_layanan;

        $unor_id = $data_utama->unor_id;

        $kelompok_absen = KelompokAbsen::select('unor_induk_id')->where('unor_id', $unor_id)->first();

        $akses_layanan = AksesLayanan::select('jabatan_id')
            ->where('id_jenis_layanan', $id_jenis_layanan)
            ->where('level', '>', $request->level)
            ->where('unor_induk_id', $kelompok_absen->unor_induk_id)
            ->orderBy('level')
            ->first();

        $jabatan_id = $akses_layanan->jabatan_id;

        $proses_layanan = new ProsesLayanan();
        $proses_layanan->id_trans_layanan = $id_trans_layanan;
        $proses_layanan->jabatan_id = $jabatan_id;
        $proses_layanan->status = 0;

        if ($proses_layanan->save()) {

            $trans_layanan->status = 1;

            $trans_layanan->save();

            TransBerkas::where('id_trans_layanan', $id_trans_layanan)
                ->update([
                    'status' => 1
                ]);

            $success = true;

            $message = 'berhasil disimpan.';

            $status_kode = 200;
        }

        return response(array(
            'success' => $success,
            'message' => $message,
        ), $status_kode);
    }

    public function listTransLayanan(Request $request)
    {
        $success = false;

        $message = 'Data tidak ditemukan.';

        $status_kode = 404;

        $data = null;

        $validator = Validator::make($request->all(), [
            'nip' => 'required|string',
            'kode_layanan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ), $status_kode);
        }

        $nip = $request->nip;

        $kode_layanan = $request->kode_layanan;

        $layanan = Layanan::select('id')->where('kode_layanan', $kode_layanan)->first();

        if (empty($layanan)) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ), $status_kode);
        }

        $id_layanan = $layanan->id;

        $jenis_layanan = JenisLayanan::select('id')->where('id_layanan', $id_layanan)->get();

        if (empty($jenis_layanan)) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ), $status_kode);
        }

        $arr_id_jenis_layanan = $jenis_layanan->pluck('id')->toArray();

        $trans_layanan = TransLayanan::select('id', 'id_jenis_layanan', 'kode_draft', 'status', 'nip', 'created_at')
            ->where('nip', $nip)
            ->whereIn('id_jenis_layanan', $arr_id_jenis_layanan)
            ->with('jenisLayanan', function ($query) {
                $query->select('m_jenis_layanan.id', 'nama_jenis', 'id_layanan', 'aktif')->with('berkasLayanan');
            })->with('berkasDraft', function ($query) {
                $query->select('tb_berkas_pegawai.id', 'nip', 'id_berkas', 'nama_berkas', 'nama_berkas_asli');
            })->with('asn', function ($query) {
                $query->select('nip_baru', 'nama', 'jabatan_nama', 'unor_id');
            })->with('prosesLayanan')
            ->orderBy('id', 'DESC')
            ->get();

        $trans_layanan_map = $trans_layanan->map(function ($val) {

            $jenis_layanan = $val['jenisLayanan']['nama_jenis'];

            $data_berkas = $val['jenisLayanan']['berkasLayanan'];

            $data_terpenuhi = $val['berkasDraft'];

            $proses_layanan = $val['prosesLayanan'];

            $jumlah_berkas = count($data_berkas);

            $jumlah_terpenuhi = count($data_terpenuhi);

            $nama_asn = $val['asn']['nama'];

            $unor_induk_id = KelompokAbsen::select('unor_induk_id')->where('unor_id', $val['asn']['unor_id'])->first()->unor_induk_id;

            $perangkat_daerah = KelompokAbsen::select('unor_nama')->where('unor_id', $unor_induk_id)->first()->unor_nama;

            $tanggal_pengajuan = Carbon::createFromFormat('Y-m-d H:i:s', $val['created_at'], 'Asia/Jakarta')->translatedFormat('j F Y');

            $jam_pengajuan = Carbon::createFromFormat('Y-m-d H:i:s', $val['created_at'], 'Asia/Jakarta')->translatedFormat('H:i');

            $is_inprogress = collect($proses_layanan)->where('status', 0)->count() > 0 ? true : false;

            $is_acc = collect($proses_layanan)->where('status', 0)->count() > 0 ? false : (collect($proses_layanan)->where('status', 2)->count() > 0 ? false : true);

            return array(
                "id" => $val['id'],
                "id_jenis_layanan" => $val['id_jenis_layanan'],
                "kode_draft" => $val['kode_draft'],
                "status" => $val['status'],
                "nip" => $val['nip'],
                "nama" => $nama_asn,
                "perangkat_daerah" => $perangkat_daerah,
                "tanggal_pengajuan" => $tanggal_pengajuan,
                "jam_pengajuan" => $jam_pengajuan,
                "jenis_layanan" => $jenis_layanan,
                "jumlah_berkas" => $jumlah_berkas,
                "jumlah_terpenuhi" => $jumlah_terpenuhi,
                "is_inprogress" => $is_inprogress,
                "is_acc" => $is_acc,
            );
        });


        if ($trans_layanan->count() > 0) {
            $success = true;
            $message = 'Data ditemukan.';
            $data = $data;
            $status_kode = 200;
        }

        if ($request->status != '') {
            $data = $request->status == 0 ? $trans_layanan_map->where('is_inprogress', true)->values()
                : ($request->status == 1 ? $trans_layanan_map->where('is_inprogress', false)->where('is_acc', true)->values()
                    : $trans_layanan_map->where('is_inprogress', false)->where('is_acc', false)->values());
        } else {
            $data = $trans_layanan_map;
        }

        return response(array(
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ), $status_kode);
    }

    public function listProsesLayanan(Request $request)
    {
        $success = false;

        $message = 'Data tidak ditemukan.';

        $status_kode = 404;

        $data = null;

        if ($request->status == 0) {
            $status = [$request->status];
        } else {
            $status = ['1', '2'];
        }

        $data_utama = DataUtama::select('nip_baru', 'jabatan_struktural_id', 'jabatan_fungsional_id', 'jabatan_fungsional_umum_id')->where('nip_baru', $request->nip)->first();

        if (empty($data_utama)) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ), $status_kode);
        }

        $jabatan_struktural_id = $data_utama->jabatan_struktural_id;

        $jabatan_fungsional_id = $data_utama->jabatan_fungsional_id;

        $jabatan_fungsional_umum_id = $data_utama->jabatan_fungsional_umum_id;

        $jabatan_id = $jabatan_struktural_id != '' ? $jabatan_struktural_id : ($jabatan_fungsional_id != '' ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);

        $layanan = Layanan::select('id')->where('kode_layanan', $request->kode_layanan)->first();

        if (empty($layanan)) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ), $status_kode);
        }

        $id_layanan = $layanan->id;

        if (empty($jabatan_id)) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ), $status_kode);
        }

        $proses = ProsesLayanan::select('id', 'id_trans_layanan', 'jabatan_id', 'status', 'verifikator_nip', 'verifikator_jabatan_id')
            ->where('jabatan_id', $jabatan_id)
            ->whereIn('status', $status)
            ->with('transLayanan', function ($query) {
                $query->select('id', 'id_jenis_layanan', 'kode_draft', 'status', 'nip', 'created_at')
                    ->with('jenisLayanan', function ($query) {
                        $query->select('m_jenis_layanan.id', 'nama_jenis', 'id_layanan', 'aktif')->with('berkasLayanan');
                    })->with('berkasDraft', function ($query) {
                        $query->select('tb_berkas_pegawai.id', 'nip', 'id_berkas', 'nama_berkas', 'nama_berkas_asli');
                    })->with('asn', function ($query) {
                        $query->select('nip_baru', 'nama', 'jabatan_nama', 'unor_id');
                    });
            })->orderBy('id', 'DESC')
            ->get();

        $jenis_layanan = JenisLayanan::where('id_layanan', $id_layanan)->get();

        if (empty($jenis_layanan)) {
            return response(array(
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ), $status_kode);
        }

        if (!empty($proses)) {


            $mapped_proses = $proses->map(function ($val) use ($jenis_layanan) {
                $nama_jenis = $val['transLayanan']['jenisLayanan']['nama_jenis'];
                $nip = $val['transLayanan']['asn']['nip_baru'];
                $nama = $val['transLayanan']['asn']['nama'];
                $status = $val['status'];
                $id_trans_layanan = $val['id_trans_layanan'];
                $jabatan_nama = $val['transLayanan']['asn']['jabatan_nama'];
                $unor_id = $val['transLayanan']['asn']['unor_id'];
                $tanggal_pengajuan = Carbon::createFromFormat('Y-m-d H:i:s', $val['transLayanan']['created_at'], 'Asia/Jakarta')->translatedFormat('j F Y');
                $unor_induk_id = KelompokAbsen::select('unor_induk_id')->where('unor_id', $unor_id)->first()->unor_induk_id;
                $unor_nama = KelompokAbsen::select('unor_nama')->where('unor_id', $unor_induk_id)->first()->unor_nama;
                $return = in_array($val['transLayanan']['jenisLayanan']['id'], $jenis_layanan->pluck('id')->toArray()) ? true : false;
                return array(
                    'id' => $val['id'],
                    'jenis_layanan' => $nama_jenis,
                    'nip' => $nip,
                    'nama' => $nama,
                    'status' => $status,
                    'return' => $return,
                    'jabatan_nama' => $jabatan_nama,
                    'perangkat_daerah' => $unor_nama,
                    'nama_jenis' => $nama_jenis,
                    'tanggal_pengajuan' => $tanggal_pengajuan,
                    'id_trans_layanan' => $id_trans_layanan,
                    'data' => $val,
                );
            });

            $success = true;

            $message = 'Data ditemukan.';

            $status_kode = 200;

            $data = $mapped_proses->where('return', true)->values();
        }

        return response(array(
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ), $status_kode);
    }

    public function detailProsesLayanan(Request $request)
    {
        $success = false;

        $message = 'Data tidak ditemukan.';

        $status_kode = 404;

        $data = null;

        $proses = ProsesLayanan::select('id', 'id_trans_layanan', 'jabatan_id', 'status', 'verifikator_nip', 'verifikator_jabatan_id', 'disposisi')
            ->where('id', $request->id_proses)
            ->with('transLayanan', function ($query) {
                $query->select('id', 'id_jenis_layanan', 'kode_draft', 'status', 'nip', 'created_at')
                    ->with('jenisLayanan', function ($query) {
                        $query->select('m_jenis_layanan.id', 'nama_jenis', 'id_layanan', 'aktif')->with('berkasLayanan');
                    })->with('berkasDraft', function ($query) {
                        $query->select('tb_berkas_pegawai.id', 'nip', 'id_berkas', 'nama_berkas', 'nama_berkas_asli');
                    })->with('asn', function ($query) {
                        $query->select('nip_baru', 'nama', 'jabatan_nama', 'unor_id');
                    });
            })->orderBy('id', 'DESC')
            ->get();

        $proses_layanan = ProsesLayanan::select('id', 'id_trans_layanan', 'jabatan_id', 'status', 'verifikator_nip', 'verifikator_jabatan_id')
            ->where('id_trans_layanan', $proses[0]['id_trans_layanan'])
            ->orderBy('id', 'DESC')
            ->get();


        $is_inprogress = collect($proses_layanan)->where('status', 0)->count() > 0 ? true : false;

        $is_acc = collect($proses_layanan)->where('status', 0)->count() > 0 ? false : (collect($proses_layanan)->where('status', 2)->count() > 0 ? false : true);

        if (!empty($proses)) {

            $mapped_proses = $proses->map(function ($val) use ($is_inprogress, $is_acc, $proses_layanan) {
                $id_jenis = $val['transLayanan']['jenisLayanan']['id'];
                $nama_jenis = $val['transLayanan']['jenisLayanan']['nama_jenis'];
                $nip = $val['transLayanan']['asn']['nip_baru'];
                $nama = $val['transLayanan']['asn']['nama'];
                $status = $val['status'];
                $id_trans_layanan = $val['id_trans_layanan'];
                $jabatan_nama = $val['transLayanan']['asn']['jabatan_nama'];
                $unor_id = $val['transLayanan']['asn']['unor_id'];
                $tanggal_pengajuan = Carbon::createFromFormat('Y-m-d H:i:s', $val['transLayanan']['created_at'], 'Asia/Jakarta')->translatedFormat('j F Y');
                $unor_induk_id = KelompokAbsen::select('unor_induk_id')->where('unor_id', $unor_id)->first()->unor_induk_id;
                $unor_nama = KelompokAbsen::select('unor_nama')->where('unor_id', $unor_induk_id)->first()->unor_nama;
                $berkas_layanan = $val['transLayanan']['jenisLayanan']['berkasLayanan'];
                $berkas_draft = $val['transLayanan']['berkasDraft'];
                $pns = DataUtama::select('id')->where('nip_baru', $nip)->first();
                $rwHukdis = RiwayatHukdis::select('hukumanTanggal', 'akhirHukumTanggal', 'jenisHukumanNama')->where('pnsOrang', $pns->id)->get();
                $date = date('Y-m-d');
                $isHukdis = false;
                $hukdisTanggal = null;
                $akhirHukdisTanggal = null;
                $jenisHukdis = null;

                if ($is_inprogress) {
                    $tracking = 'Menunggu verifikasi ' . $proses_layanan->first()->jabatan;
                } else {
                    $tracking = 'Selesai verifikasi ' . $proses_layanan->first()->verifikator;
                }

                if (!empty($rwHukdis)) {
                    foreach ($rwHukdis as $x) {
                        $hukumanTanggal = Carbon::createFromFormat('d-m-Y', $x->hukumanTanggal, 'Asia/Jakarta');
                        $akhirHukumTanggal = Carbon::createFromFormat('d-m-Y', $x->akhirHukumTanggal, 'Asia/Jakarta');

                        if ($date >= $hukumanTanggal->translatedFormat('Y-m-d') && $date <= $akhirHukumTanggal->translatedFormat('Y-m-d')) {
                            $isHukdis = true;
                            $hukdisTanggal = $hukumanTanggal->translatedFormat('j F Y');
                            $akhirHukdisTanggal = $akhirHukumTanggal->translatedFormat('j F Y');
                            $jenisHukdis = $x->jenisHukumanNama;
                        }
                    }
                }

                return array(
                    'id' => $val['id'],
                    'id_jenis_layanan' => $id_jenis,
                    'jenis_layanan' => $nama_jenis,
                    'nip' => $nip,
                    'nama' => $nama,
                    'status' => $status,
                    'is_inprogress' => $is_inprogress,
                    'is_acc' => $is_acc,
                    'tracking' => $tracking,
                    'disposisi' => $val['disposisi'],
                    'jabatan_nama' => $jabatan_nama,
                    'perangkat_daerah' => $unor_nama,
                    'nama_jenis' => $nama_jenis,
                    'tanggal_pengajuan' => $tanggal_pengajuan,
                    'id_trans_layanan' => $id_trans_layanan,
                    'berkas_layanan' => $berkas_layanan,
                    'berkas_draft' => $berkas_draft,
                    'is_hukdis' => $isHukdis,
                    'tanggal_hukuman' => $hukdisTanggal,
                    'akhir_tanggal_hukuman' => $akhirHukdisTanggal,
                    'jenis_hukdis' => ucfirst(strtolower($jenisHukdis)),
                );
            });

            $success = true;

            $message = 'Data ditemukan.';

            $status_kode = 200;

            $data = $mapped_proses;
        }

        return response(array(
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ), $status_kode);
    }

    public function detailTransLayanan(Request $request)
    {
        $success = false;

        $message = 'Data tidak ditemukan.';

        $status_kode = 404;

        $data = null;

        $proses_layanan = ProsesLayanan::select('id', 'id_trans_layanan', 'jabatan_id', 'status', 'verifikator_nip', 'verifikator_jabatan_id', 'disposisi')
            ->where('id_trans_layanan', $request->id_trans_layanan)
            ->with('transLayanan', function ($query) {
                $query->select('id', 'id_jenis_layanan', 'kode_draft', 'status', 'nip', 'created_at')
                    ->with('jenisLayanan', function ($query) {
                        $query->select('m_jenis_layanan.id', 'nama_jenis', 'id_layanan', 'aktif')->with('berkasLayanan');
                    })->with('berkasDraft', function ($query) {
                        $query->select('tb_berkas_pegawai.id', 'nip', 'id_berkas', 'nama_berkas', 'nama_berkas_asli');
                    })->with('asn', function ($query) {
                        $query->select('nip_baru', 'nama', 'jabatan_nama', 'unor_id');
                    });
            })->limit(1)->orderBy('id', 'DESC')
            ->get();


        $is_inprogress = collect($proses_layanan)->where('status', 0)->count() > 0 ? true : false;

        $is_acc = collect($proses_layanan)->where('status', 0)->count() > 0 ? false : (collect($proses_layanan)->where('status', 2)->count() > 0 ? false : true);

        if (!empty($proses_layanan)) {

            $mapped_proses = $proses_layanan->map(function ($val) use ($is_inprogress, $is_acc, $proses_layanan) {
                $id_jenis = $val['transLayanan']['jenisLayanan']['id'];
                $nama_jenis = $val['transLayanan']['jenisLayanan']['nama_jenis'];
                $nip = $val['transLayanan']['asn']['nip_baru'];
                $nama = $val['transLayanan']['asn']['nama'];
                $status = $val['status'];
                $id_trans_layanan = $val['id_trans_layanan'];
                $jabatan_nama = $val['transLayanan']['asn']['jabatan_nama'];
                $unor_id = $val['transLayanan']['asn']['unor_id'];
                $tanggal_pengajuan = Carbon::createFromFormat('Y-m-d H:i:s', $val['transLayanan']['created_at'], 'Asia/Jakarta')->translatedFormat('j F Y');
                $unor_induk_id = KelompokAbsen::select('unor_induk_id')->where('unor_id', $unor_id)->first()->unor_induk_id;
                $unor_nama = KelompokAbsen::select('unor_nama')->where('unor_id', $unor_induk_id)->first()->unor_nama;
                $berkas_layanan = $val['transLayanan']['jenisLayanan']['berkasLayanan'];
                $berkas_draft = $val['transLayanan']['berkasDraft'];
                if ($is_inprogress) {
                    $tracking = 'Menunggu verifikasi ' . $proses_layanan->first()->jabatan;
                } else {
                    $tracking = 'Selesai verifikasi ' . $proses_layanan->first()->verifikator;
                }

                return array(
                    'id' => $val['id'],
                    'id_jenis_layanan' => $id_jenis,
                    'jenis_layanan' => $nama_jenis,
                    'nip' => $nip,
                    'nama' => $nama,
                    'status' => $status,
                    'is_inprogress' => $is_inprogress,
                    'is_acc' => $is_acc,
                    'tracking' => $tracking,
                    'disposisi' => $val['disposisi'],
                    'jabatan_nama' => $jabatan_nama,
                    'perangkat_daerah' => $unor_nama,
                    'nama_jenis' => $nama_jenis,
                    'tanggal_pengajuan' => $tanggal_pengajuan,
                    'id_trans_layanan' => $id_trans_layanan,
                    'berkas_layanan' => $berkas_layanan,
                    'berkas_draft' => $berkas_draft,
                );
            });

            $success = true;

            $message = 'Data ditemukan.';

            $status_kode = 200;

            $data = $mapped_proses;
        }

        return response(array(
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ), $status_kode);
    }

    public function cekAksesLayanan(Request $request)
    {
        $success = false;

        $message = 'Akses tidak diizinkan.';

        $status_kode = 400;

        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'kode_layanan' => 'required'
        ]);

        if ($validator->fails()) {
            return response(array(
                'success' => $success,
                'message' => $message,
            ), $status_kode);
        }

        $data_utama = DataUtama::select('jabatan_struktural_id', 'jabatan_fungsional_id', 'jabatan_fungsional_umum_id')->where('nip_baru', $request->nip)->first();

        if (empty($data_utama)) {
            return response(array(
                'success' => $success,
                'message' => $message,
            ), $status_kode);
        }

        $jabatan_struktural_id = $data_utama->jabatan_struktural_id;

        $jabatan_fungsional_id = $data_utama->jabatan_fungsional_id;

        $jabatan_fungsional_umum_id = $data_utama->jabatan_fungsional_umum_id;

        $jabatan_id = $jabatan_struktural_id != '' ? $jabatan_struktural_id : ($jabatan_fungsional_id != '' ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);

        if ($jabatan_id == '') {
            return response(array(
                'success' => $success,
                'message' => $message,
            ), $status_kode);
        }

        $akses_layanan = AksesLayanan::where('jabatan_id', $jabatan_id)
            ->with('jenisLayanan', function ($query) {
                $query->select('id', 'id_layanan')->with('layanan', function ($query) {
                    $query->select('id', 'kode_layanan');
                });
            })
            ->get();

        $mapped_akses_layanan = $akses_layanan->map(function ($val) {
            return array(
                'id' => $val['id'],
                'kode_layanan' => $val['jenisLayanan']['layanan']['kode_layanan'],
            );
        });

        if ($mapped_akses_layanan->where('kode_layanan', $request->kode_layanan)->count() > 0) {
            $success = true;
            $message = 'Akses diizinkan.';
            $status_kode = 200;
        }

        return response(array(
            'success' => $success,
            'message' => $message,
        ), $status_kode);
    }

    public function updateProsesLayanan(Request $request)
    {

        $success = false;

        $message = 'Update gagal.';

        $status_kode = 400;

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nip' => 'required',
            'status' => 'required',
            'id_jenis_layanan' => 'required',
        ]);

        if ($validator->fails()) {
            return response(array(
                'success' => $success,
                'message' => $message,
            ), $status_kode);
        }

        $data_utama = DataUtama::select('nip_baru', 'nama', 'unor_id', 'jabatan_struktural_id', 'jabatan_fungsional_id', 'jabatan_fungsional_umum_id')->where('nip_baru', $request->nip)->first();

        if (empty($data_utama)) {
            return response(array(
                'success' => $success,
                'message' => 'Data ASN tidak ditemukan.',
            ), $status_kode);
        }

        $unor_id = $data_utama->unor_id;

        $jabatan_struktural_id = $data_utama->jabatan_struktural_id;

        $jabatan_fungsional_id = $data_utama->jabatan_fungsional_id;

        $jabatan_fungsional_umum_id = $data_utama->jabatan_fungsional_umum_id;

        $jabatan_id = $jabatan_struktural_id != '' ? $jabatan_struktural_id : ($jabatan_fungsional_id != '' ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);

        if ($jabatan_id == '') {
            return response(array(
                'success' => $success,
                'message' => 'Jabatan tidak tersedia.',
            ), $status_kode);
        }

        $kelompok_absen = KelompokAbsen::select('unor_induk_id')->where('unor_id', $unor_id)->first();

        if (empty($kelompok_absen)) {
            return response(array(
                'success' => $success,
                'message' => 'Kelompok absen tidak tersedia.',
            ), $status_kode);
        }

        $unor_induk_id = $kelompok_absen->unor_induk_id;

        $proses_layanan = ProsesLayanan::where('id', $request->id)->first();

        if (empty($proses_layanan)) {
            return response(array(
                'success' => $success,
                'message' => 'Proses layanan tidak ditemukan.',
            ), $status_kode);
        }

        $id_trans_layanan = $proses_layanan->id_trans_layanan;

        $akses_layanan = AksesLayanan::where('unor_induk_id', $unor_induk_id)
            ->where('jabatan_id', $jabatan_id)
            ->where('id_jenis_layanan', $request->id_jenis_layanan)
            ->first();

        if (empty($akses_layanan)) {
            return response(array(
                'success' => $success,
                'message' => 'Akses tidak diizinkan.',
            ), $status_kode);
        }

        $level_sekarang = $akses_layanan->level;

        $level_berikutnya = $level_sekarang + 1;

        $akses_layanan_berikutnya = AksesLayanan::where('unor_induk_id', $unor_induk_id)
            ->where('id_jenis_layanan', $request->id_jenis_layanan)
            ->where('level', $level_berikutnya)
            ->first();

        if (empty($akses_layanan_berikutnya)) {

            $jumlah_proses_layanan = ProsesLayanan::where('id_trans_layanan', $id_trans_layanan)->count();

            if ($jumlah_proses_layanan >= 1) {
                $akses_layanan_berikutnya = AksesLayanan::where('id_jenis_layanan', $request->id_jenis_layanan)
                    ->where('is_verifikator', true)
                    ->where('level', '>', $akses_layanan->level)
                    ->orderBy('level')
                    ->first();
                // if (empty($akses_layanan_berikutnya)) {
                //     return response(array(
                //         'success' => $success,
                //         'message' => 'Layanan tidak dapat diteruskan.',
                //     ), $status_kode);
                // }
            } else {
                return response(array(
                    'success' => $success,
                    'message' => 'Layanan tidak dapat diteruskan!',
                ), $status_kode);
            }
        }

        $proses_layanan->status = $request->status;
        $proses_layanan->verifikator_nip = $request->nip;
        $proses_layanan->verifikator_jabatan_id = $jabatan_id;
        $proses_layanan->disposisi = $request->disposisi;
        $proses_layanan->save();

        if ($request->status == 1) {
            // if ((!$akses_layanan->is_verifikator && !$akses_layanan_berikutnya->is_verifikator) ||  (!$akses_layanan->is_verifikator && $akses_layanan_berikutnya->is_verifikator)) {
            if (!empty($akses_layanan_berikutnya)) {
                $proses_layanan_baru = new ProsesLayanan();
                $proses_layanan_baru->id_trans_layanan = $id_trans_layanan;
                $proses_layanan_baru->jabatan_id = $akses_layanan_berikutnya->jabatan_id;
                $proses_layanan_baru->save();
            }
        }

        $success = true;
        $message = 'Permohonan berhasil diproses.';
        $status_kode = 200;

        return response(array(
            'success' => $success,
            'message' => $message,
        ), $status_kode);
    }
}
