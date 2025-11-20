@section('breadcrumb')
    Riwayat Diklat
@endsection
@section('styles')
    {{-- styles --}}
@endsection
@section('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            appendSelect();
        });

        document.addEventListener('livewire:update', function() {
            appendSelect();
        });

        function appendSelect() {
            $('#select2').select2({
                ajax: {
                    delay: 500,
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

            $('#btn-save').on('click', function() {
                $('#btn-save').attr('disabled', true);
                $('#btn-close').attr('disabled', true);
                $('#btn-simpan').html('<i class=fa fa-spinner fa-spin""></i> menyimpan..');
                var form_data = new FormData(document.getElementById("form-diklat"));
                console.log(form_data);
                $.ajax({
                    type: 'post',
                    url: '{{ route('usulan-riwayat-diklat.store') }}',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#btn-save').removeAttr('disabled');
                        $('#btn-save').html('Save changes');
                        $('#btn-close').removeAttr('disabled');
                        console.log('sukses');
                        console.log(response);
                    },
                    error: function(error) {
                        $('#btn-save').removeAttr('disabled');
                        $('#btn-save').html('Save changes');
                        $('#btn-close').removeAttr('disabled');
                        alert(JSON.parse(error.responseText)['message']);
                    }
                });
            });

            $('#nama-diklat').on('change',function(){
                var namaDiklat = $('#nama-diklat :selected').text();
                console.log(namaDiklat);
                
                $('#id-diklat').val(namaDiklat);
            })

            $('#scan-sertifikat').on('change', function() {
                    var fileName = $(this).val();
                    console.log(fileName);

                    $(this).next('.custom-file-label').html(fileName);
                })
        }
    </script>
@endsection

<div>
    <div class="btn-toolbar mb-3 row col-md-12" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group d-flex align-items-stretch mb-md-15 col-md-6 col-sm-12 col-12" role="group"
            aria-label="First group">
            <button type="button" class="btn btn-info btn-sm"><i class="icon-copy dw dw-down-arrow-2"></i> Sync
                Diklat</button>
            <button type="button" class="btn btn-info btn-sm"><i class="icon-copy dw dw-down-arrow1"></i>
                Export</button>
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                data-target="#add-diklat-modal-content" data-backdrop="static" data-keyboard="false"><i
                    class="icon-copy dw dw-add"></i> Tambah Data</button>
        </div>
        <div class="mb-4 position-relative col-md-6 col-sm-6 col-12">
            <input id="search" type="text" wire:model.debounce.500ms="search" class="form-control"
                placeholder="Cari Riwayat Diklat...">
            <div wire:loading wire:target="search" class="position-absolute"
                style="top: 50%; right: 10px; transform: translateY(-50%);">
                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
            </div>
        </div>
    </div>
    <table class="table .table-responsive">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Diklat</th>
                <th scope="col">Institusi Penyelenggara</th>
                <th scope="col">Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rw_diklat as $item)
                <tr>
                    <td class="text-center">{{ ($currentPage - 1) * $perPage + $loop->iteration }}</td>
                    <td>{{ $item->latihanStrukturalNama }}</td>
                    <td>{{ $item->institusiPenyelenggara }}</td>
                    <td>{{ Carbon\Carbon::createFromFormat('d-m-Y', $item->tanggalSelesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    <nav>
        <ul class="btn-group d-flex align-items-stretch mb-md-15 col-md-6 col-sm-12 col-12">
            @if (!$rw_diklat->onFirstPage())
                <button type="button" wire:click="previousPage" class="btn btn-info btn-sm">Previous</button>
            @else
                <button class="btn btn-info btn-sm disabled" disabled>Previous</button>
            @endif

            @foreach ($page_links as $page)
                <button wire:click="gotoPage({{ $page }})"
                    class="btn btn-info btn-sm {{ $page == $rw_diklat->currentPage() ? 'disabled' : '' }}"
                    {{ $page == $rw_diklat->currentPage() ? 'disabled' : '' }}>{{ $page }}</button>
            @endforeach

            @if ($rw_diklat->hasMorePages())
                <button type="button" wire:click="nextPage" class="btn btn-info btn-sm">Next</button>
            @else
                <button class="btn btn-info btn-sm disabled" disabled>Next</button>
            @endif
        </ul>
    </nav>
    <div id="add-diklat-modal">
        <div class="modal fade bs-example-modal-lg" id="add-diklat-modal-content" role="dialog"
            aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">Tambah Riwayat Diklat</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" id="form-diklat">
                            @csrf()
                            <input type="hidden" name="jenis-riwayat" value="9">
                            <div class="col-md-12 col-lg-12 col-sm-12 rounded" style="background: aliceblue">
                                <div class="form-group row">
                                    <label for="select2"
                                        class="d-flex align-items-center col-sm-12 col-md-1 col-form-label select2-label">ASN:</label>
                                    <div class="col-sm-12 col-md-11">
                                        <select class="form-control form-control-sm" id="select2" name="asn"
                                            style="width: 100%; height: 38px;">
                                            <option selected value="{{ Auth::user()->v2Profile->nip_baru }}">
                                                {{ Auth::user()->v2Profile->nama . ' - ' . Auth::user()->v2Profile->nip_baru }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 pl-0">
                                    <div class="form-group">
                                        <label for="jenis-diklat">Jenis Diklat</label>
                                        <select name="jenis-diklat" id="jenis-diklat" class="form-control form-control-sm">
                                            <option selected disabled value="1">- Pilih Jenis Diklat -</option>
                                            <option value="1">Diklat Struktural</option>
                                        </select>
                                    </div>
                                </div>
                                <dic class="col-md-6 col-sm-12 pr-0">
                                    <div class="form-group">
                                        <label for="nama-diklat">Nama Diklat</label>
                                        <select name="nama-diklat" id="nama-diklat" class="form-control form-control-sm">
                                            <option selected disabled value="0">- Pilih Nama Diklat-</option>
                                            @forelse($ref_diklat_struktural as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        <input type="hidden" name="id-diklat" id="id-diklat">
                                    </div>
                                </dic>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="institusi-penyelenggara">Institusi Penyelenggara</label>
                                        <input id="institusi-penyelenggara" name="institusi-penyelenggara"
                                            class="form-control form-control-sm" type="text"
                                            placeholder="Institusi Penyelenggara">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 pl-0">
                                    <div class="form-group">
                                        <label for="no-sertifikat">No Sertifikat</label>
                                        <input name="no-sertifikat" id="no-sertifikat" type="text"
                                            class="form-control form-control-sm" placeholder="No Sertifikat">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="tanggal-mulai">Tanggal Mulai</label>
                                        <div class="input-group">
                                            <input name="tanggal-mulai" id="tanggal-mulai" type="text"
                                                class="form-control form-control-sm date-picker" placeholder="Tanggal"
                                                aria-describedby="btnGroupAddon">
                                            <div class="input-group-prepend bg-info rounded-right">
                                                <div class="input-group-text text-light" id="btnGroupAddonTglMulai">Pilih
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                </div>
                                <div class="col-md-3 col-sm-6 pr-0">
                                    <div class="form-group">
                                        <label for="tanggal-selesai">Tanggal Selesai</label>
                                        <div class="input-group">
                                            <input name="tanggal-selesai" id="tanggal-selesai" type="text"
                                                class="form-control form-control-sm date-picker" placeholder="Tanggal"
                                                aria-describedby="btnGroupAddon">
                                            <div class="input-group-prepend bg-info rounded-right">
                                                <div class="input-group-text text-light" id="btnGroupAddonTglSelesai">
                                                    Pilih</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9 col-sm-12 pl-0">
                                    <div class="form-group">
                                        <label for="scan-sertifikat">Scan Sertifikat</label>
                                        <div class="custom-file">
                                            <input accept="application/pdf" name="scan-sertifikat" id="scan-sertifikat" type="file"
                                                class="custom-file-input">
                                            <label for="scan-sertifikat" class="custom-file-label">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 pr-0">
                                    <div class="form-group">
                                        <label for="durasi-jam">Durasi Jam</label>
                                        <input id="durasi-jam" name="durasi-jam" class="form-control form-control-sm"
                                            type="number" placeholder="Durasi Jam">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btn-close" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button id="btn-save" type="button" class="btn btn-info">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
