<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;


class RequestDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $nip;

    protected $url;

    protected $target;

    protected $key;

    protected $parameter;

    public $timeout = 90;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($nip,$url,$target,$key=null,$parameter=null)
    {
        $this->nip = $nip;

        $this->url = $url;

        $this->target = $target;

        $this->key = $key;

        $this->parameter = $parameter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $token = DB::table('db_simpeg_v2.auth')->select('*')->orderBy('id','DESC')->limit(1)->get();

        $authToken = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTMxNDcsImlhdCI6MTczMTkwOTk0NywianRpIjoiNTBkN2YwZWQtNTYwMC00MDAxLWIxMTEtN2MzODExYTI5M2I4IiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6WyJpZGlzIiwiYWNjb3VudCJdLCJzdWIiOiJhYjllMjI1MC00MWZkLTQ0OWMtYjFlZi01MWE3MGI4MDQ2ODkiLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJwYWRhbmdrb3Rhd3MiLCJzZXNzaW9uX3N0YXRlIjoiYzczNTM5YWMtYzUxNS00ZWEyLWI0NTUtNmI3YWJiM2FjNGJhIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmtpcmltLXVzdWwtcmluY2lhbi1mb3JtYXNpIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1tb25pdG9yLXBlcmVuY2FuYWFuLWtlcGVnYXdhaWFuIiwicm9sZTpzaWFzbi1pbnN0YW5zaTprcDphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjpyZWtvbiIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpza2s6YXBwcm92YWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktb3BlcmF0b3Itc290ayIsInJvbGU6ZGFzaGJvYXJkLW9wZXJhc2lvbmFsOmluc3RhbnNpIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjp1c3VsLXJpbmNpYW4tZm9ybWFzaSIsInJvbGU6ZGlzcGFrYXRpOmluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlbWJlcmhlbnRpYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOmlwYXNuOm1vbml0b3JpbmciLCJyb2xlOnNpYXNuLWluc3RhbnNpOmtwOnBhcmFmIiwicm9sZTpzaWFzbi1pbnN0YW5zaTp0bS1pbnN0YW5zaSIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVtYmVyaGVudGlhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktcGVuZXRhcGFuLXNvdGsiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46cGFyYWYiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpza2s6cGFyYWYiLCJyb2xlOnNpYXNuLWluc3RhbnNpOmFkbWluOmFkbWluIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiaWRpcyI6eyJyb2xlcyI6WyJhZ2VuY3ktYWRtaW4iXX0sImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlRFR1VIIFNVV0FOREEiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTkxMDQyNDIwMTUwMjEwMDIiLCJnaXZlbl9uYW1lIjoiVEVHVUgiLCJmYW1pbHlfbmFtZSI6IlNVV0FOREEiLCJlbWFpbCI6InN1d2FuZGEudGVndWhAZ21haWwuY29tIn0.XftJ00ZAW6r_q06N9Nl_q_W8xSSS3421HxQRzgooZTCD7P7w3y0tmfHidpmzCAD3yM5Jl4iE7dOxg2IxsYvlYQYbAQTtJTGKcbyLKtRkn6ZWG4h6P_vm_TnnossH-MnJD1MZ0eMfeZXujBhMRIt3o2VX_LOuzx62gyWuJrCFoqg1E9bp2MYu9fmvfH6p1u_RQbxz1cByAcIbmpzZpOHNNZYj8Mmn1AdRdJC_azKcD9psmCJLZhYEJwAofew6q-khKWTREj9PNghvs8dsdQYlLPcHHQsBoMEq63qfzjq02u6tsgr8YMyaKYU8fwHQY0iFvC4c_Yva51aiEa6VfnZ06g';

        if($token->count()==0)
        {
            $newToken = $this->getToken();
            $result = $this->tryToken($newToken['authorizationToken']);
            if($result)
            {
                $this->fetchData($authToken,$newToken['authorizationToken'],$this->nip);
            }
        }else{
            $result = $this->tryToken($token->first()->authorizationToken);
            if($result)
            {
                $this->fetchData($authToken,$token->first()->authorizationToken,$this->nip);
            }else{
                $newToken = $this->getToken();
                $result = $this->tryToken($newToken['authorizationToken']);
                if($result)
                {
                    $this->fetchData($authToken,$newToken['authorizationToken'],$this->nip);
                }
            }
        }

    }

    public function getToken()
    {
        // $authToken = Http::asForm()->post('https://sso-siasn.bkn.go.id/auth/realms/public-siasn/protocol/openid-connect/token', [
        //                 'client_id' => 'padangkotaws',
        //                 'grant_type' => 'password',
        //                 'username' => '199104242015021002',
        //                 'password' => 'Pemko1234'
        //             ]);

        $authorizationToken = Http::withBasicAuth('f0Mwkq1udsBNkaE0aGeVjEftJUEa','d48MBaJ92Xhapg7tJQaYrWJVa7Ma')
                                ->post('https://apimws.bkn.go.id/oauth2/token',[
                                    'grant_type' => 'client_credentials'
                                ]);

        // if ($authToken->failed()) {

        //     throw new RuntimeException("Gagal mendapatkan Auth Token! ".$authToken, $authToken->status());
        // }

        if ($authorizationToken->failed()) {

            throw new RuntimeException("Gagal Mendapatkan Authorization Token! ".$authorizationToken, $authorizationToken->status());
        }

        $insert = DB::table('db_simpeg_v2.auth')->insert([
            'authorizationToken' => $authorizationToken['access_token']
        ]);

        if($insert)
        {
            return [
                'authorizationToken' => $authorizationToken['access_token']
            ];
        }

        return false;
    }

    public function tryToken($authorizationToken)
    {
        $http_request = Http::withHeaders([
            'accept' => 'application/json',
            'Auth' => 'bearer eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3MzE5NTMxNDcsImlhdCI6MTczMTkwOTk0NywianRpIjoiNTBkN2YwZWQtNTYwMC00MDAxLWIxMTEtN2MzODExYTI5M2I4IiwiaXNzIjoiaHR0cHM6Ly9zc28tc2lhc24uYmtuLmdvLmlkL2F1dGgvcmVhbG1zL3B1YmxpYy1zaWFzbiIsImF1ZCI6WyJpZGlzIiwiYWNjb3VudCJdLCJzdWIiOiJhYjllMjI1MC00MWZkLTQ0OWMtYjFlZi01MWE3MGI4MDQ2ODkiLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJwYWRhbmdrb3Rhd3MiLCJzZXNzaW9uX3N0YXRlIjoiYzczNTM5YWMtYzUxNS00ZWEyLWI0NTUtNmI3YWJiM2FjNGJhIiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmtpcmltLXVzdWwtcmluY2lhbi1mb3JtYXNpIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1tb25pdG9yLXBlcmVuY2FuYWFuLWtlcGVnYXdhaWFuIiwicm9sZTpzaWFzbi1pbnN0YW5zaTprcDphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjpyZWtvbiIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpza2s6YXBwcm92YWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnNrazpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktb3BlcmF0b3Itc290ayIsInJvbGU6ZGFzaGJvYXJkLW9wZXJhc2lvbmFsOmluc3RhbnNpIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjp1c3VsLXJpbmNpYW4tZm9ybWFzaSIsInJvbGU6ZGlzcGFrYXRpOmluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlbWJlcmhlbnRpYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOmlwYXNuOm1vbml0b3JpbmciLCJyb2xlOnNpYXNuLWluc3RhbnNpOmtwOnBhcmFmIiwicm9sZTpzaWFzbi1pbnN0YW5zaTp0bS1pbnN0YW5zaSIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVtYmVyaGVudGlhbjphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktcGVuZXRhcGFuLXNvdGsiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46cGFyYWYiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpza2s6cGFyYWYiLCJyb2xlOnNpYXNuLWluc3RhbnNpOmFkbWluOmFkbWluIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiaWRpcyI6eyJyb2xlcyI6WyJhZ2VuY3ktYWRtaW4iXX0sImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IlRFR1VIIFNVV0FOREEiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTkxMDQyNDIwMTUwMjEwMDIiLCJnaXZlbl9uYW1lIjoiVEVHVUgiLCJmYW1pbHlfbmFtZSI6IlNVV0FOREEiLCJlbWFpbCI6InN1d2FuZGEudGVndWhAZ21haWwuY29tIn0.XftJ00ZAW6r_q06N9Nl_q_W8xSSS3421HxQRzgooZTCD7P7w3y0tmfHidpmzCAD3yM5Jl4iE7dOxg2IxsYvlYQYbAQTtJTGKcbyLKtRkn6ZWG4h6P_vm_TnnossH-MnJD1MZ0eMfeZXujBhMRIt3o2VX_LOuzx62gyWuJrCFoqg1E9bp2MYu9fmvfH6p1u_RQbxz1cByAcIbmpzZpOHNNZYj8Mmn1AdRdJC_azKcD9psmCJLZhYEJwAofew6q-khKWTREj9PNghvs8dsdQYlLPcHHQsBoMEq63qfzjq02u6tsgr8YMyaKYU8fwHQY0iFvC4c_Yva51aiEa6VfnZ06g',
            'Authorization' => 'Bearer '.$authorizationToken
        ])->get('https://apimws.bkn.go.id:8243/apisiasn/1.0/pns/data-utama/'.$this->nip);

        if($http_request->failed())
        {
            Log::info('try token success.');
            if(isset($http_request['code']))
            {
                if($http_request['code']=="900803")
                {
                    throw new RuntimeException("Exceeded Quota! Skipped ".$this->nip.' | '.$http_request, $http_request->status());
                }
            }

            return false;
        }else{
            Log::info('try token failed.');
        }

        return true;
    }

    public function fetchData($authToken,$authorizationToken,$nip)
    {
        $http_request = Http::withHeaders([
            'accept' => 'application/json',
            'Auth' => 'bearer '.$authToken,
            'Authorization' => 'Bearer '.$authorizationToken
        ])->get($this->url);

        if($http_request->failed())
        {
            Log::info('fetch data '.$nip.' failed.');
            throw new RuntimeException("Gagal Mendapatkan Data! ".$http_request, $http_request->status());
        }

        if($this->target=='data_utama')
        {
            $old_data = DB::table('db_simpeg_v2.data_utama')->select('*')->where('nip_baru',$nip)->first();

            if(!empty($old_data))
            {
                DB::table('db_simpeg_v2.temp_data_utama')->insert(json_decode(json_encode($old_data),true));
            }
    
            $query = DB::table('db_simpeg_v2.data_utama')->updateOrInsert(
                [
                    'nip_baru' => $http_request['data']['nipBaru']
                ],
                [
                    'id' => $http_request['data']['id'],
                    'nip_baru' => $http_request['data']['nipBaru'],
                    'nip_lama' => $http_request['data']['nipLama'],
                    'nama' => $http_request['data']['nama'],
                    'gelar_depan' => $http_request['data']['gelarDepan'],
                    'gelar_belakang' => $http_request['data']['gelarBelakang'],
                    'tempat_lahir' => $http_request['data']['tempatLahir'],
                    'tempat_lahir_id' => $http_request['data']['tempatLahirId'],
                    'tanggal_lahir' => $http_request['data']['tglLahir'],
                    'agama' => $http_request['data']['agama'],
                    'agama_id' => $http_request['data']['agamaId'],
                    'email' => $http_request['data']['email'],
                    'nik' => $http_request['data']['nik'],
                    'alamat' => $http_request['data']['alamat'],
                    'no_hp' => $http_request['data']['noHp'],
                    'no_telpon' => $http_request['data']['noTelp'],
                    'jenis_pegawai_id' => $http_request['data']['jenisPegawaiId'],
                    'status_pegawai' => $http_request['data']['statusPegawai'],
                    'kedudukan_pns_id' => $http_request['data']['kedudukanPnsId'],
                    'kedudukan_pns_nama' => $http_request['data']['kedudukanPnsNama'],
                    'jenis_kelamin' => $http_request['data']['jenisKelamin'],
                    'jenis_id_dokumen_id' => $http_request['data']['jenisIdDokumenId'],
                    'jenis_id_dokumen_nama' => $http_request['data']['jenisIdDokumenNama'],
                    'nomor_id_document' => $http_request['data']['nomorIdDocument'],
                    'no_seri_karpeg' => $http_request['data']['noSeriKarpeg'],
                    'tk_pendidikan_terakhir_id' => $http_request['data']['tkPendidikanTerakhirId'],
                    'tk_pendidikan_terakhir' => $http_request['data']['tkPendidikanTerakhir'],
                    'pendidikan_terakhir_id' => $http_request['data']['pendidikanTerakhirId'],
                    'tahun_lulus' => $http_request['data']['tahunLulus'],
                    'tmt_pns' => $http_request['data']['tmtPns'],
                    'tanggal_sk_pns' => $http_request['data']['tglSkPns'],
                    'tmt_cpns' => $http_request['data']['tmtCpns'],
                    'tanggal_sk_cpns' => $http_request['data']['tglSkCpns'],
                    'instansi_induk_id' => $http_request['data']['instansiIndukId'],
                    'instansi_induk_nama' => $http_request['data']['instansiIndukNama'],
                    'satuan_kerja_induk_id' => $http_request['data']['satuanKerjaIndukId'],
                    'satuan_kerja_induk_nama' => $http_request['data']['satuanKerjaIndukNama'],
                    'kanreg_id' => $http_request['data']['kanregId'],
                    'kanreg_nama' => $http_request['data']['kanregNama'],
                    'instansi_kerja_id' => $http_request['data']['instansiKerjaId'],
                    'instansi_kerja_nama' => $http_request['data']['instansiKerjaNama'],
                    'instansi_kerja_kode_cepat' => $http_request['data']['instansiKerjaKodeCepat'],
                    'satuan_kerja_kerja_id' => $http_request['data']['satuanKerjaKerjaId'],
                    'satuan_kerja_kerja_nama' => $http_request['data']['satuanKerjaKerjaNama'],
                    'unor_id' => $http_request['data']['unorId'],
                    'unor_nama' => $http_request['data']['unorNama'],
                    'unor_induk_id' => $http_request['data']['unorIndukId'],
                    'unor_induk_nama' => $http_request['data']['unorIndukNama'],
                    'jenis_jabatan_id' => $http_request['data']['jenisJabatanId'],
                    'jenis_jabatan' => $http_request['data']['jenisJabatan'],
                    'jabatan_nama' => $http_request['data']['jabatanNama'],
                    'jabatan_struktural_id' => $http_request['data']['jabatanStrukturalId'],
                    'jabatan_struktural_nama' => $http_request['data']['jabatanStrukturalNama'],
                    'jabatan_fungsional_id' => $http_request['data']['jabatanFungsionalId'],
                    'jabatan_fungsional_nama' => $http_request['data']['jabatanFungsionalNama'],
                    'jabatan_fungsional_umum_id' => $http_request['data']['jabatanFungsionalUmumId'],
                    'jabatan_fungsional_umum_nama' => $http_request['data']['jabatanFungsionalUmumNama'],
                    'tmt_jabatan' => $http_request['data']['tmtJabatan'],
                    'lokasi_kerja_id' => $http_request['data']['lokasiKerjaId'],
                    'lokasi_kerja' => $http_request['data']['lokasiKerja'],
                    'gol_ruang_awal_id' => $http_request['data']['golRuangAwalId'],
                    'gol_ruang_awal' => $http_request['data']['golRuangAwal'],
                    'gol_ruang_akhir_id' => $http_request['data']['golRuangAkhirId'],
                    'gol_ruang_akhir' => $http_request['data']['golRuangAkhir'],
                    'tmt_gol_akhir' => $http_request['data']['tmtGolAkhir'],
                    'masa_kerja' => $http_request['data']['masaKerja'],
                    'eselon' => $http_request['data']['eselon'],
                    'eselon_id' => $http_request['data']['eselonId'],
                    'eselon_level' => $http_request['data']['eselonLevel'],
                    'tmt_eselon' => $http_request['data']['tmtEselon'],
                    'gaji_pokok' => $http_request['data']['gajiPokok'],
                    'kpkn_id' => $http_request['data']['kpknId'],
                    'kpkn_nama' => $http_request['data']['kpknNama'],
                    'ktua_id' => $http_request['data']['ktuaId'],
                    'taspen_id' => $http_request['data']['taspenId'],
                    'taspen_nama' => $http_request['data']['taspenNama'],
                    'jenis_kawin_id' => $http_request['data']['jenisKawinId'],
                    'status_perkawinan' => $http_request['data']['statusPerkawinan'],
                    'status_hidup' => $http_request['data']['statusHidup'],
                    'tanggal_surat_keterangan_dokter' => $http_request['data']['tglSuratKeteranganDokter'],
                    'no_surat_keterangan_dokter' => $http_request['data']['noSuratKeteranganDokter'],
                    'jumlah_istri_suami' => $http_request['data']['jumlahIstriSuami'],
                    'jumlah_anak' => $http_request['data']['jumlahAnak'],
                    'no_surat_keterangan_bebas_narkoba' => $http_request['data']['noSuratKeteranganBebasNarkoba'],
                    'tanggal_surat_keterangan_bebas_narkoba' => $http_request['data']['tglSuratKeteranganBebasNarkoba'],
                    'skck' => $http_request['data']['skck'],
                    'tanggal_skck' => $http_request['data']['tglSkck'],
                    'akte_kelahiran' => $http_request['data']['akteKelahiran'],
                    'akte_meninggal' => $http_request['data']['akteMeninggal'],
                    'tanggal_meninggal' => $http_request['data']['tglMeninggal'],
                    'no_npwp' => $http_request['data']['noNpwp'],
                    'tanggal_npwp' => $http_request['data']['tglNpwp'],
                    'no_askes' => $http_request['data']['noAskes'],
                    'bpjs' => $http_request['data']['bpjs'],
                    'kode_pos' => $http_request['data']['kodePos'],
                    'no_spmt' => $http_request['data']['noSpmt'],
                    'no_taspen' => $http_request['data']['noTaspen'],
                    'bahasa' => $http_request['data']['bahasa'],
                    'kppn_id' => $http_request['data']['kppnId'],
                    'kppn_nama' => $http_request['data']['kppnNama'],
                    'pangkat_akhir' => $http_request['data']['pangkatAkhir'],
                    'tanggal_sttpl' => $http_request['data']['tglSttpl'],
                    'no_sttpl' => $http_request['data']['nomorSttpl'],
                    'no_sk_cpns' => $http_request['data']['nomorSkCpns'],
                    'no_sk_pns' => $http_request['data']['nomorSkPns'],
                    'jenjang' => $http_request['data']['jenjang'],
                    'jabatan_asn' => $http_request['data']['jabatanAsn'],
                    'kartu_asn' => $http_request['data']['kartuAsn']
                ]
            );
    
            if($query)
            {
                DB::table('db_simpeg_v2.tb_log')->insert([
                    'pegawai' => $http_request['data']['nama'].' ('.$http_request['data']['nipBaru'].')',
                    'keterangan' => $this->target.' telah diperbarui.'
                ]);

                $temp_data = DB::table('db_simpeg_v2.temp_data_utama')->select('*')->where('nip_baru',$nip)->first();
    
                if(!empty($temp_data))
                {
                    DB::table('db_simpeg_v2.log_data_utama')->insert(json_decode(json_encode($temp_data),true));
                }
            }else{
                DB::table('db_simpeg_v2.tb_log')->insert([
                    'pegawai' => $http_request['data']['nama'].' ('.$http_request['data']['nipBaru'].')',
                    'keterangan' => 'Tidak ada pembaruan '.$this->target
                ]);
            }
    
            DB::table('db_simpeg_v2.temp_data_utama')->truncate();
        }else{
            $old_data = DB::table('db_simpeg_v2.'.$this->target)->select('*')->where($this->key!=null ? $this->key:'nipBaru',$this->parameter!=null ? $this->parameter:$this->nip)->first();

            if(!empty($old_data))
            {
                DB::table('db_simpeg_v2.'.'temp_'.$this->target)->insert(json_decode(json_encode($old_data),true));
            }

            if(is_array($http_request['data']))
            {
                $data_collection = collect($http_request['data'])->map(function ($item) {
                    $item['path'] = isset($item['path']) ? json_encode($item['path']):null;
                    $item['id'] = isset($item['id']) ? $item['id']:$item['ID'];
                    return $item;
                })->values();
            }

            if(isset($data_collection))
            {
                foreach($data_collection as $x)
                {
                    $query = DB::table('db_simpeg_v2.'.$this->target)->updateOrInsert(['id' => $x['id']],$x);
            
                    if($query)
                    {
                        DB::table('db_simpeg_v2.'.'tb_log')->insert([
                            'pegawai' => $x['nipBaru'],
                            'keterangan' => $this->target.' telah diperbarui.'
                        ]);
        
                        $temp_data = DB::table('db_simpeg_v2.'.'temp_'.$this->target)
                                        ->select('*')
                                        ->where('id',$x['id'])
                                        ->first();
            
                        if(!empty($temp_data))
                        {
                            DB::table('db_simpeg_v2.'.'log_'.$this->target)->insert(json_decode(json_encode($temp_data),true));
                        }
                    }else{
                        DB::table('db_simpeg_v2.'.'tb_log')->insert([
                            'pegawai' => $this->nip,
                            'keterangan' => 'Tidak ada pembaruan '.$this->target
                        ]);
                    }
            
                    DB::table('db_simpeg_v2.'.'temp_'.$this->target)->truncate();
                }
            }
        }

        Log::info('fetch data '.$nip.' success.');

    }

    public function retryAfter()
    {
        return 120;
    }
}
