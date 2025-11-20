@section('breadcrumb')
    Riwayat Hukdis
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ url('vendor/alertify/css/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendor/alertify/css/themes/default.min.css') }}">
@endsection
@section('scripts')
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="{{ url('vendor/alertify/alertify.min.js') }}"></script>
    <script>
        $('#select2').select2({
            ajax: {
                url: '{{ route('search-pegawai') }}',
                dataType: 'json',
                processResults: function(data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 10) < data.count_filtered
                        },
                    };
                },
            }
        });

        $('#alasan-hukuman').select2({
            width: '100%'
        });
    </script>
@endsection

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden shadow-xl sm:rounded-lg">
            <div class="card-box height-100-p">
                <div class="row align-items-center">
                    <div class="col-md-4 d-flex justify-content-center">
                        <lottie-player src="{{ url('vendor/images/animation-hammer.json') }}" background="##FFFFFF"
                            speed="0.5" style="width: 300px; height: 300px" autoplay direction="1"
                            mode="normal"></lottie-player>
                    </div>
                    <div class="col-md-8">
                        <h4>
                            <p class="max-width-600 text-justify">
                                Peraturan Pemerintah Republik Indonesia (PP)<br>Nomor 94 Tahun 2021 tentang Disiplin
                                Pegawai Negeri Sipil (PNS).
                            </p>
                        </h4>
                        <p class="max-width-600 text-justify">
                            PNS wajib menaati kewajiban dan menghindari larangan yang sebagaimana tercantum dalam Pasal
                            2 sampai dengan Pasal 5.
                            PNS yang tidak menaati ketentuan tersebut, dapat dijatuhi hukuman disiplin, mulai dari
                            hukuman ringan, sedang, hingga berat.
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="btn-toolbar mb-3 row col-md-12" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group d-flex align-items-stretch mb-md-15 col-md-6 col-sm-12 col-12" role="group"
                        aria-label="First group">
                        <button type="button" class="btn btn-info btn-sm"><i class="icon-copy dw dw-down-arrow-2"></i>
                            Sync Hukdis</button>
                        <button type="button" class="btn btn-info btn-sm"><i class="icon-copy dw dw-down-arrow1"></i>
                            Export</button>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                            data-target="#add-hukdis-modal-content" data-backdrop="static" data-keyboard="false"><i
                                class="icon-copy dw dw-add"></i> Tambah Data</button>
                    </div>
                    <div class="input-group col-md-3 col-sm-6 col-12">
                        <div class="input-group-prepend bg-info rounded-left">
                            <div class="input-group-text text-light" id="btnGroupAddonOpd">OPD</div>
                        </div>
                        <input name="unit-kerja" type="text" class="form-control form-control-sm h-100"
                            placeholder="Pilih Unit Kerja" aria-label="Pilih Unit Kerja"
                            aria-describedby="btnGroupAddon">
                    </div>
                    <div class="input-group col-md-3 col-sm-6 col-12">
                        <input name="cari-pegawai" type="text" class="form-control form-control-sm h-100"
                            placeholder="Cari Pegawai" aria-label="Cari Pegawai" aria-describedby="btnGroupAddon">
                        <div class="input-group-prepend bg-info rounded-right">
                            <div class="input-group-text text-light" id="btnGroupAddonPegawai">Pegawai</div>
                        </div>
                    </div>
                </div>
                {{-- @if ($hak_akses) --}}
                <div class="row">
                    <table class="data-table table nowrap w-100">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">NIP</th>
                                <th class="text-center">Hukuman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data_hukdis as $x)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $x->dataUtama->nama }}</td>
                                    <td class="text-center">{{ $x->dataUtama->nip_baru }}</td>
                                    <td>{{ $x->jenisHukumanNama }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- @endif --}}
            </div>
            <div id="add-hukdis-modal">
                <div class="modal fade bs-example-modal-lg" id="add-hukdis-modal-content" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Tambah Riwayat Hukdis</h4>
                                <button type="button" class="close" data-dismiss="modal">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12 col-lg-12 col-sm-12 rounded" style="background: aliceblue">
                                    <div class="form-group row">
                                        <label for="select2"
                                            class="d-flex align-items-center col-sm-12 col-md-1 col-form-label select2-label">ASN:</label>
                                        <div class="col-sm-12 col-md-11">
                                            <select class="form-control form-control-sm" id="select2" name="asn"
                                                style="width: 100%; height: 38px;">
                                                <option selected disabled
                                                    value="{{ Auth::user()->v2Profile->nip_baru }}">
                                                    {{ Auth::user()->v2Profile->nama . ' - ' . Auth::user()->v2Profile->nip_baru }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="kategori">Kategori Hukuman Disiplin</label>
                                            <select name="kategori" id="kategori"
                                                class="form-control form-control-sm">
                                                <option selected disabled ="1">- Pilih Kategori -</option>
                                                <option value="1">Penetapan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <dic class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="tingkat">Tingkat Hukuman Disiplin</label>
                                            <select name="tingkat" id="tingkat"
                                                class="form-control form-control-sm">
                                                <option selected disabled value="1">- Pilih tingkat -</option>
                                                <option value="R">Ringan</option>
                                                <option value="S">Sedang</option>
                                                <option value="B">Berat</option>
                                            </select>
                                        </div>
                                    </dic>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="no-sk">NO SK Hukuman Disiplin</label>
                                            <input type="text" class="form-control form-control-sm" name="no-sk"
                                                id="no-sk" placeholder="Nomor SK Hukuman Disiplin">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="no-peraturan">Nomor Peraturan</label>
                                            <select name="no-peraturan" id="no-peraturan"
                                                class="form-control form-control-sm input-sm">
                                                <option value="1">- Pilih No PP -</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="tanggal-sk-hukdis">Tanggal SK Hukdis</label>
                                            <div class="input-group">
                                                <input name="tanggal-sk-hukdis" id="tanggal-sk-hukdis" type="text"
                                                    class="form-control form-control-sm date-picker"
                                                    placeholder="Tanggal" aria-describedby="btnGroupAddon">
                                                <div class="input-group-prepend bg-info rounded-right">
                                                    <div class="input-group-text text-light"
                                                        id="btnGroupAddonTglHukdis">Pilih</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="tmt-hukdis">TMT Hukdis</label>
                                            <div class="input-group">
                                                <input name="tmt-hukdis" id="tmt-hukdis" type="text"
                                                    class="form-control form-control-sm date-picker"
                                                    placeholder="Tanggal" aria-describedby="btnGroupAddon">
                                                <div class="input-group-prepend bg-info rounded-right">
                                                    <div class="input-group-text text-light"
                                                        id="btnGroupAddonTmtHukdis">Pilih</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="alasan-hukuman">Alasan Hukuman</label>
                                            <select name="alasan-hukuman" id="alasan-hukuman"
                                                class="form-control form-control-sm">
                                                <option selected disabled value="1">- Pilih Alasan -</option>
                                                @forelse($ref_alasan_hukdis as $item)
                                                    <option value="{{ $item->idSiasn }}">{{ $item->nama }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="masa-hukuman-tahun">Masa Hukuman Tahun</label>
                                            <input name="masa-hukuman-tahun" id="masa-hukuman-tahun" type="text"
                                                class="form-control form-control-sm" placeholder="Tahun">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="masa-hukuman-bulan">Masa Hukuman Bulan</label>
                                            <input name="masa-hukuman-bulan" id="masa-hukuman-bulan" type="text"
                                                class="form-control form-control-sm" placeholder="Bulan">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="keterangan">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sk-hukdis">SK Hukdis</label>
                                            <div class="custom-file">
                                                <input name="sk-hukdis" id="sk-hukdis" type="file"
                                                    class="custom-file-input">
                                                <label class="custom-file-label" for="sk-hukdis">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sk-pengaktifan">SK Pengaktifan Hukuman</label>
                                            <div class="custom-file">
                                                <input name="sk-pengaktifan" id="sk-pengaktifan" type="file"
                                                    class="custom-file-input">
                                                <label class="custom-file-label" for="sk-pengaktifan">Choose
                                                    file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-info" data-dismiss="modal">Save
                                    changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
