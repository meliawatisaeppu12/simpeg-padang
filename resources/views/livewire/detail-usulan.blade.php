<div class="row w-100">
    <div class="row w-100 border-bottom">
        <div class="btn-toolbar mb-3 row col-md-12" role="toolbar" aria-label="Toolbar with button groups">
            <div class="col-md-3 col-sm-6">
                <a class="btn btn-info btn-sm" href="{{ route('list-usulan') }}"><i class="fa fa-arrow-left"></i>
                    Kembali</a>
            </div>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-center">
        <div class="row mt-4 w-75">

            <div class="d-flex justify-content-center w-100 m-2 border-bottom p-2">
                <div>{{ __('Usulan Riwayat ') . ucwords(strtolower($data->jenisRiwayat)) }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('NIP') }}</div>
                <div>{{ $data->nip }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('Nama') }}</div>
                <div>{{ $data->nama }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('Jenis Jabatan') }}</div>
                <div>{{ $data->jenisJabatan }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('Jenis Mutasi') }}</div>
                <div>{{ $data->jenisMutasiId == 'MJ' ? 'Mutasi Jabatan' : 'Mutasi Unor' }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('Unit Kerja') }}</div>
                <div>{{ $data->unor_nama }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('Nama Jabatan') }}</div>
                <div>{{ $data->namaJabatan }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('No SK') }}</div>
                <div>{{ $data->nomorSk }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('Tanggal SK') }}</div>
                <div>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tanggalSk)->translatedFormat('j F Y') }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('TMT Mutasi') }}</div>
                <div>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tmtMutasi)->translatedFormat('j F Y') }}</div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('TMT Pelantikan') }}</div>
                <div>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->tmtPelantikan)->translatedFormat('j F Y') }}
                </div>
            </div>
            <div class="d-flex justify-content-between w-100 m-2 border-bottom p-2">
                <div>{{ __('SK Jabatan') }}</div>
                <div><a href="{{ Route('berkas.usulan', [Crypt::encrypt($data->nip), Crypt::encrypt($data->skJabatan)]) }}"
                        target="_blank" class="text-blue"><i class="fa fa-download"></i> {{__('Unduh')}}</a></div>
            </div>
            @if ($akses)
                <div class="d-flex justify-content-end w-100 m-2 p-2">
                    <button class="btn btn-danger btn-sm m-2"><i class="fa fa-close"></i> Tolak</button>
                    <button class="btn btn-primary btn-sm m-2"><i class="fa fa-check"></i> Terima</button>
                </div>
            @endif
        </div>
    </div>
</div>
