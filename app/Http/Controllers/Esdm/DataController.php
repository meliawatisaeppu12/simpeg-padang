<?php

namespace App\Http\Controllers\Esdm;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function storeUnitOrganisasi(){

        return $this->dataJabatan();

        // return DB::table('m_unit_organisasi')->insert($this->dataUnitOrganisasi()->all());
    }

    public function dataJabatan()
    {
        // return app(BotController::class)->getUpdates();
    }

    public function dataUnitOrganisasi()
    {

        return DB::table('db_simpeg_v2.data_utama')->select('unor_id', 'unor_nama', 'unor_induk_id', 'eselon_id')->get()->groupBy('unor_id')->map(function ($value) {

            return collect($value)->map(function ($value) {

                $needles = strtolower($value->unor_nama);

                $sekolah_keys = array(
                    'sd ',
                    'sdn',
                    'smp ',
                    'smpn',
                    'tk',
                    'sekolah'
                );
            
                $puskesmas_keys = array(
                    'pusat kesehatan masyarakat'
                );
            
                $ignore_keys = array(
                    'dinas',
                    'seksi',
                    'upt',
                    'tata usaha',
                    'bidang'
                );

                return array(

                    'unor_id' => $value->unor_id,

                    'unor_nama' => $value->unor_nama,

                    'unor_induk_id' => $value->unor_induk_id,

                    'jenis_id' => $this->strContains($needles, $sekolah_keys, $ignore_keys) ? '1' : ($this->strContains($needles, $puskesmas_keys,['sub bagian']) ? '2' : '99')

                    // 'jenis_id' => $value->eselon_id
                );
            })->first();

        })->where('unor_induk_id','!=',null)->values();
    }

    public function dataOrganisasi()
    {

        return DB::table('db_simpeg_v2.data_utama')
                    ->select('unor_id', 'unor_nama', 'unor_induk_id')
                    ->whereRaw("(unor_nama LIKE '%dinas%' OR unor_nama LIKE '%kantor%' OR unor_nama LIKE '%badan%' OR unor_nama LIKE '%sekretariat daerah%' OR unor_nama LIKE '%sekretariat dprd%' OR unor_nama LIKE '%kecamatan%') AND (unor_nama NOT LIKE '%bagian%' AND unor_nama NOT LIKE '%seksi%' AND unor_nama NOT LIKE '%bidang%' AND unor_nama NOT LIKE '%negeri%' AND unor_nama NOT LIKE '%kelurahan%' AND unor_nama NOT LIKE '%upt%' AND unor_nama NOT LIKE '%tk%' AND unor_nama NOT LIKE '%sd%')")
                    ->get()
                    ->groupBy('unor_id')
                    ->map(function ($value) {

                        return collect($value)->map(function ($value) {

                            return array(

                                'unor_induk_id' => $value->unor_id,

                                'unor_induk_nama' => $value->unor_nama
                            );
                        })->first();

                    })->values();
    }

    private function strContains(string $haystack, array $needles, array $ignores = null)
    {

        foreach ($needles as $needle) {

            if (!empty($ignores)) {

                $counter = 0;

                foreach ($ignores as $ignore) {

                    if (str_contains($haystack, $needle) && !str_contains($haystack, $ignore)) {

                        $counter++;
                    }
                }

                if (str_contains($haystack, $needle)) {

                    return $counter == count($ignores) ? true : false;
                }
            } else {

                if (str_contains($haystack, $needle)) {

                    return true;
                }
            }
        }

        return false;
    }
}
