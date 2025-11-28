<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Simpeg\UploadHistory;

class DataPegawai implements ToModel,WithHeadingRow,WithBatchInserts
{
    use Importable;

    /**
    * @param Array $array
    */
    public function model(array $array)
    {
        return new UploadHistory([
            'PNS_ID' => $array['pns_id'],
            'NIP_BARU' => str_replace("'","",$array['nip_baru']),
            'NAMA' => $array['nama'],
            'JABATAN_NAMA' => $array['jabatan_nama']
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
