    @section('breadcrumb')
        Riwayat Jabatan
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

                $('#nama-jabatan').select2({
                    ajax: {
                        delay: 500,
                        url: '{{ route('search-jabatan-fungsional') }}',
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
                    },
                    width: '100%'
                });

                $('#unit-kerja').select2({
                    width: '100%'
                });


                $('#jenis-jabatan').on('change', function() {
                    var val = $(this).val();
                    if (val != 1) {
                        $('#div-sub-jabatan').removeClass('d-none');
                        $('#sub-jabatan').select2({
                            width: '100%'
                        });
                    } else {
                        $('#div-sub-jabatan').addClass('d-none');
                    }
                });

                $('#btn-save').on('click', function() {
                    $('#btn-save').attr('disabled', true);
                    $('#btn-close').attr('disabled', true);
                    $('#btn-simpan').html('<i class=fa fa-spinner fa-spin""></i> menyimpan..');
                    var form_data = new FormData(document.getElementById("form-jabatan"));
                    console.log(form_data);
                    $.ajax({
                        type: 'post',
                        url: '{{ route('usulan-riwayat-jabatan.store') }}',
                        data: form_data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            window.location.replace("{{Route('list-usulan')}}");
                        },
                        error: function(error) {
                            $('#btn-save').removeAttr('disabled');
                            $('#btn-save').html('Save changes');
                            $('#btn-close').removeAttr('disabled');
                            alert(JSON.parse(error.responseText)['message']);
                        }
                    });
                });

                $('#sk-jabatan').on('change', function() {
                    var fileName = $(this).val();
                    console.log(fileName);

                    $(this).next('.custom-file-label').html(fileName);
                })

                $('#nama-jabatan').on('change', function() {
                    var namaJabatan = $('#nama-jabatan :selected').text();
                    $('#id-jabatan').val(namaJabatan)
                })

                $('.datepicker').datepicker({
                    format: 'mm/dd/yyyy'
                })
            }
        </script>
    @endsection

    <div>
        <div class="btn-toolbar mb-3 row col-md-12" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group d-flex align-items-stretch mb-md-15 col-md-6 col-sm-12 col-12" role="group"
                aria-label="First group">
                <button type="button" class="btn btn-info btn-sm"><i class="icon-copy dw dw-down-arrow-2"></i> Sync
                    Jabatan</button>
                <button type="button" class="btn btn-info btn-sm"><i class="icon-copy dw dw-down-arrow1"></i>
                    Export</button>
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                    data-target="#add-jabatan-modal-content" data-backdrop="static" data-keyboard="false"><i
                        class="icon-copy dw dw-add"></i> Tambah Data</button>
            </div>
            <div class="mb-4 position-relative col-md-6 col-sm-6 col-12">
                <input id="search" type="text" wire:model.debounce.500ms="search" class="form-control"
                    placeholder="Cari Riwayat Jabatan...">
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
                    <th scope="col">Jabatan</th>
                    <th scope="col">Unit Organisasi</th>
                    <th scope="col">Unit Organisai Induk</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rw_jabatan as $item)
                    <tr>
                        <td class="text-center">{{ ($currentPage - 1) * $perPage + $loop->iteration }}</td>
                        <td>{{ !empty($item->namaJabatan) ? $item->namaJabatan : (!empty($item->jabatanFungsionalNama) ? $item->jabatanFungsionalNama : $item->jabatanFungsionalUmumNama) }}
                        </td>
                        <td>{{ $item->unorNama }}</td>
                        <td>{{ $item->unorIndukNama }}</td>
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
                @if (!$rw_jabatan->onFirstPage())
                    <button type="button" wire:click="previousPage" class="btn btn-info btn-sm">Previous</button>
                @else
                    <button class="btn btn-info btn-sm disabled" disabled>Previous</button>
                @endif

                @foreach ($page_links as $page)
                    <button wire:click="gotoPage({{ $page }})"
                        class="btn btn-info btn-sm {{ $page == $rw_jabatan->currentPage() ? 'disabled' : '' }}"
                        {{ $page == $rw_jabatan->currentPage() ? 'disabled' : '' }}>{{ $page }}</button>
                @endforeach

                @if ($rw_jabatan->hasMorePages())
                    <button type="button" wire:click="nextPage" class="btn btn-info btn-sm">Next</button>
                @else
                    <button class="btn btn-info btn-sm disabled" disabled>Next</button>
                @endif
            </ul>
        </nav>
        <div id="add-jabatan-modal">
            <div class="modal fade bs-example-modal-lg" id="add-jabatan-modal-content" role="dialog"
                aria-labelledby="myLargeModalLabel">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Tambah Riwayat Jabatan</h4>
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="form-jabatan" enctype="multipart/form-data" method="post">
                                @csrf()
                                <input type="hidden" name="jenis-riwayat" value="8">
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
                                            <label for="jenis-jabatan">Jenis Jabatan</label>
                                            <select name="jenis-jabatan" id="jenis-jabatan"
                                                class="form-control form-control-sm">
                                                <option selected disabled value="0">- Jenis Jabatan -</option>
                                                @forelse($ref_jenis_jabatan as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <dic class="col-md-6 col-sm-12 pr-0">
                                        <div class="form-group">
                                            <label for="jenis-mutasi">Jenis Mutasi</label>
                                            <select name="jenis-mutasi" id="jenis-mutasi"
                                                class="form-control form-control-sm">
                                                <option selected disabled value="0">-Pilih Jenis Mutasi-</option>
                                                <option value="MJ">Mutasi Jabatan</option>
                                                <option value="MU">Mutasi Unor</option>
                                            </select>
                                        </div>
                                    </dic>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="unit-kerja">Unit Kerja</label>
                                            <select name="unit-kerja" id="unit-kerja"
                                                class="w-100 form-control form-control-sm">
                                                <option value="1" selected disabled>- Pilih Unit Kerja -</option>
                                                @foreach ($ref_unor as $items)
                                                    <option value="{{ $items->unor_id }}">{{ $items->unor_nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="nama-jabatan">Nama Jabatan</label>
                                            <select name="nama-jabatan" id="nama-jabatan"
                                                class="form-control form-control-sm">
                                                <option selected disabled value="1">- Nama Jabatan -</option>
                                            </select>
                                            <input type="hidden" name="id-jabatan" id="id-jabatan">
                                        </div>
                                    </div>
                                </div>
                                <div id="div-sub-jabatan" class="row d-none">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="sub-jabatan">Sub Jabatan</label>
                                            <select name="sub-jabatan" id="sub-jabatan"
                                                class="form-control form-control-sm">
                                                <option selected disabled value="0">- Pilih Sub Jabatan -</option>
                                                @forelse ($ref_sub_jabatan as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 pl-0">
                                        <div class="form-group">
                                            <label for="no-sk">No SK</label>
                                            <input name="no-sk" id="no-sk" type="text"
                                                class="form-control form-control-sm" placeholder="No SK">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="tanggal-sk">Tanggal SK</label>
                                            <div class="input-group">
                                                <input name="tanggal-sk" id="tanggal-sk" type="text"
                                                    class="form-control form-control-sm date-picker"
                                                    placeholder="Tanggal" aria-describedby="btnGroupAddon">
                                                <div class="input-group-prepend bg-info rounded-right">
                                                    <div class="input-group-text text-light" id="btnGroupAddonTglSk">
                                                        Pilih
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-3 col-sm-6 pr-0">
                                        <div class="form-group">
                                            <label for="tmt-mutasi">TMT Mutasi</label>
                                            <div class="input-group">
                                                <input name="tmt-mutasi" id="tmt-mutasi" type="text"
                                                    class="form-control form-control-sm date-picker"
                                                    placeholder="Tanggal" aria-describedby="btnGroupAddon">
                                                <div class="input-group-prepend bg-info rounded-right">
                                                    <div class="input-group-text text-light"
                                                        id="btnGroupAddonTmtMutasi">
                                                        Pilih</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9 col-sm-12 pl-0">
                                        <div class="form-group">
                                            <label for="sk-jabatan">Scan SK</label>
                                            <div class="custom-file">
                                                <input name="sk-jabatan" id="sk-jabatan" type="file"
                                                    class="custom-file-input" accept="application/pdf">
                                                <label for="sk-jabatan" class="custom-file-label">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 pr-0">
                                        <div class="form-group">
                                            <label for="tmt-pelantikan">TMT Pelantikan</label>
                                            <div class="input-group">
                                                <input name="tmt-pelantikan" id="tmt-pelantikan" type="text"
                                                    class="form-control form-control-sm date-picker"
                                                    placeholder="Tanggal" aria-describedby="btnGroupAddon">
                                                <div class="input-group-prepend bg-info rounded-right">
                                                    <div class="input-group-text text-light"
                                                        id="btnGroupAddonTmtMutasi">
                                                        Pilih</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button id="btn-close"type="button" class="btn btn-danger"
                                data-dismiss="modal">Close</button>
                            <button id="btn-save" type="button" class="btn btn-info">
                                <span>Save changes</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
