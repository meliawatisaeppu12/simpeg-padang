<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Interfaces\PelaksanaInterface;
use Illuminate\Http\Request;

class DisposisiWorkflowController extends Controller
{
    public function fordwardDisposisi(
        int $id,
        PelaksanaInterface $pengirim,
        PelaksanaInterface $tujuan,
        String $telitiSaran
    ) {

    }
}
